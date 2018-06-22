<?php
/**
 * all controller for show info from all table
 *
 * @category controller
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 *
 * @param introField =`` //introtext
 * @param contentField =`description` //content
 * @param table =`` //table name
 */
class onetableDocLister extends DocLister
{
    /**
     * @var string
     */
    protected $table = '';
    /**
     * @var string
     */
    protected $idField = 'id';

    /**
     * @var string
     */
    protected $parentField = 'parent';

    /**
     * Экземпляр экстендера пагинации
     * @var null|paginate_DL_Extender
     */
    protected $extPaginate = null;

    /**
     * @abstract
     */
    public function getDocs($tvlist = '')
    {
        if ($this->extPaginate = $this->getExtender('paginate')) {
            $this->extPaginate->init($this);
        }
        $type = $this->getCFGDef('idType', 'parents');
        $this->_docs = ($type == 'parents') ? $this->getChildrenList() : $this->getDocList();

        return $this->_docs;
    }

    /**
     * @param string $tpl
     * @return string
     */
    public function _render($tpl = '')
    {
        $out = '';
        $separator = $this->getCFGDef('outputSeparator', '');
        if ($tpl == '') {
            $tpl = $this->getCFGDef('tpl', '');
        }
        if ($tpl != '') {
            $this->toPlaceholders(count($this->_docs), 1, "display"); // [+display+] - сколько показано на странице.

            $i = 1;
            $sysPlh = $this->renameKeyArr($this->_plh, $this->getCFGDef("sysKey", "dl"));
            $noneTPL = $this->getCFGDef("noneTPL", "");
            if (count($this->_docs) == 0 && $noneTPL != '') {
                $out = $this->parseChunk($noneTPL, $sysPlh);
            } else {
                /**
                 * @var $extUser user_DL_Extender
                 */
                if ($extUser = $this->getExtender('user')) {
                    $extUser->init($this, array('fields' => $this->getCFGDef("userFields", "")));
                }

                /**
                 * @var $extSummary summary_DL_Extender
                 */
                $extSummary = $this->getExtender('summary');

                /**
                 * @var $extPrepare prepare_DL_Extender
                 */
                $extPrepare = $this->getExtender('prepare');
                $this->skippedDocs = 0;
                foreach ($this->_docs as $item) {
                    $this->renderTPL = $tpl;
                    if ($extUser) {
                        $item = $extUser->setUserData($item); //[+user.id.createdby+], [+user.fullname.publishedby+], [+dl.user.publishedby+]....
                    }

                    $item[$this->getCFGDef("sysKey", "dl") . '.summary'] = $extSummary ? $this->getSummary(
                        $item,
                        $extSummary
                    ) : '';

                    $item = array_merge(
                        $item,
                        $sysPlh
                    ); //inside the chunks available all placeholders set via $modx->toPlaceholders with prefix id, and with prefix sysKey
                    $item[$this->getCFGDef(
                        "sysKey",
                        "dl"
                    ) . '.iteration'] = $i; //[+iteration+] - Number element. Starting from zero

                    $date = $this->getCFGDef('dateSource', 'pub_date');
                    if (isset($item[$date])) {
                        $_date = is_numeric($item[$date]) && $item[$date] == (int)$item[$date] ? $item[$date] : strtotime($item[$date]);
                        if ($_date !== false) {
                            $_date = $_date + $this->modx->config['server_offset_time'];
                            $dateFormat = $this->getCFGDef('dateFormat', '%d.%b.%y %H:%M');
                            if ($dateFormat) {
                                $item['date'] = strftime($dateFormat, $_date);
                            }
                        }
                    }

                    $findTpl = $this->renderTPL;
                    $tmp = $this->uniformPrepare($item, $i);
                    extract($tmp, EXTR_SKIP);
                    if ($this->renderTPL == '') {
                        $this->renderTPL = $findTpl;
                    }

                    if ($extPrepare) {
                        $item = $extPrepare->init($this, array(
                            'data'      => $item,
                            'nameParam' => 'prepare'
                        ));
                        if (is_bool($item) && $item === false) {
                            $this->skippedDocs++;
                            continue;
                        }
                    }
                    $tmp = $this->parseChunk($this->renderTPL, $item);
                    if ($this->getCFGDef('contentPlaceholder', 0) !== 0) {
                        $this->toPlaceholders(
                            $tmp,
                            1,
                            "item[" . $i . "]"
                        ); // [+item[x]+] – individual placeholder for each iteration documents on this page
                    }
                    $out .= $tmp;
                    if (next($this->_docs) !== false) {
                        $out .= $separator;
                    }
                    $i++;
                }
            }
            $out = $this->renderWrap($out);
        }

        return $this->toPlaceholders($out);
    }

    /**
     * @param array $data
     * @param mixed $fields
     * @param array $array
     * @return string
     */
    public function getJSON($data, $fields, $array = array())
    {
        $out = array();
        $fields = is_array($fields) ? $fields : explode(",", $fields);
        $date = $this->getCFGDef('dateSource', 'pub_date');

        /**
         * @var $extSummary summary_DL_Extender
         */
        $extSummary = $this->getExtender('summary');

        /**
         * @var $extPrepare prepare_DL_Extender
         */
        $extPrepare = $this->getExtender('prepare');

        /**
         * @var $extE e_DL_Extender
         */
        $extE = $this->getExtender('e', true, true);
        foreach ($data as $num => $row) {
            switch (true) {
                case ((array('1') == $fields || in_array('summary', $fields)) && $extSummary):
                    $row['summary'] = $this->getSummary($row, $extSummary, 'introtext');
                //without break
                case ((array('1') == $fields || in_array('date', $fields)) && $date != 'date'):
                    if (isset($row[$date])) {
                        $_date = is_numeric($row[$date]) && $row[$date] == (int)$row[$date] ? $row[$date] : strtotime($row[$date]);
                        if ($_date !== false) {
                            $_date = $_date + $this->modx->config['server_offset_time'];
                            $dateFormat = $this->getCFGDef('dateFormat', '%d.%b.%y %H:%M');
                            if ($dateFormat) {
                                $row['date'] = strftime($dateFormat, $_date);
                            }
                        }
                    }
                //nobreak
            }

            if ($extE && $tmp = $extE->init($this, array('data' => $row))) {
                if (is_array($tmp)) {
                    $row = $tmp;
                }
            }

            if ($extPrepare) {
                $row = $extPrepare->init($this, array('data' => $row));
                if (is_bool($row) && $row === false) {
                    continue;
                }
            }
            $out[$num] = $row;
        }

        return parent::getJSON($out, $fields, $out);
    }

    /**
     * @return array
     */
    protected function getDocList()
    {
        $out = array();
        $sanitarInIDs = $this->sanitarIn($this->IDs);
        if ($sanitarInIDs != "''" || $this->getCFGDef('ignoreEmpty', '0')) {
            $from = $this->table . " " . $this->_filters['join'];
            $where = $this->getCFGDef('addWhereList', '');

            //====== block added by Dreamer to enable filters ======
            $where = ($where ? $where . ' AND ' : '') . $this->_filters['where'];
            $where = sqlHelper::trimLogicalOp($where);
            //------- end of block -------


            if ($where != '') {
                $where = array($where);
            } else {
                $where = array();
            }
            if ($sanitarInIDs != "''") {
                $where[] = "{$this->getPK()} IN ({$sanitarInIDs})";
            }

            if (!empty($where)) {
                $where = "WHERE " . implode(" AND ", $where);
            } else {
                $where = '';
            }

            $limit = $this->LimitSQL($this->getCFGDef('queryLimit', 0));
            $fields = $this->getCFGDef('selectFields', '*');
            $group = $this->getGroupSQL($this->getCFGDef('groupBy', ''));
            $sort = $this->SortOrderSQL($this->getPK());
            $rs = $this->dbQuery("SELECT {$fields} FROM {$from} {$where} {$group} {$sort} {$limit}");

            $pk = $this->getPK(false);
            while ($item = $this->modx->db->getRow($rs)) {
                $out[$item[$pk]] = $item;
            }
        }

        return $out;
    }

    /**
     * @return array
     */
    protected function getChildrenList()
    {
        $where = array();
        $out = array();
        $from = $this->table . " " . $this->_filters['join'];
        $tmpWhere = $this->getCFGDef('addWhereList', '');
        $tmpWhere = sqlHelper::trimLogicalOp($tmpWhere);

        //====== block added by Dreamer to enable filters ======
        $tmpWhere = ($tmpWhere ? $tmpWhere . ' AND ' : '') . $this->_filters['where'];
        $tmpWhere = sqlHelper::trimLogicalOp($tmpWhere);
        //------- end of block -------


        if (!empty($tmpWhere)) {
            $where[] = $tmpWhere;
        }
        $sanitarInIDs = $this->sanitarIn($this->IDs);

        $tmpWhere = null;
        if ($sanitarInIDs != "''") {
            $tmpWhere = "({$this->getParentField()} IN (" . $sanitarInIDs . ")";
            switch ($this->getCFGDef('showParent', '0')) {
                case -1:
                    $tmpWhere .= ")";
                    break;
                case 0:
                    $tmpWhere .= " AND {$this->getPK()} NOT IN(" . $sanitarInIDs . "))";
                    break;
                case 1:
                default:
                    $tmpWhere .= " OR {$this->getPK()} IN({$sanitarInIDs}))";
                    break;
            }
        }
        if (($addDocs = $this->getCFGDef('documents', '')) != '') {
            $addDocs = $this->sanitarIn($this->cleanIDs($addDocs));
            if (empty($tmpWhere)) {
                $tmpWhere = $this->getPK() . " IN({$addDocs})";
            } else {
                $tmpWhere = "((" . $tmpWhere . ") OR {$this->getPK()} IN({$addDocs}))";
            }
        }
        if (!empty($tmpWhere)) {
            $where[] = $tmpWhere;
        }
        if (!empty($where)) {
            $where = "WHERE " . implode(" AND ", $where);
        } else {
            $where = '';
        }
        $fields = $this->getCFGDef('selectFields', '*');
        $group = $this->getGroupSQL($this->getCFGDef('groupBy', ''));
        $sort = $this->SortOrderSQL($this->getPK());
        $limit = $this->LimitSQL($this->getCFGDef('queryLimit', 0));
        if ($sanitarInIDs != "''" || $this->getCFGDef('ignoreEmpty', '0')) {
            $rs = $this->dbQuery("SELECT {$fields} FROM {$from} {$where} {$group} {$sort} {$limit}");

            $pk = $this->getPK(false);

            while ($item = $this->modx->db->getRow($rs)) {
                $out[$item[$pk]] = $item;
            }
        }

        return $out;
    }

    /**
     * @absctract
     */
    public function getChildrenCount()
    {
        $out = 0;
        $sanitarInIDs = $this->sanitarIn($this->IDs);
        if ($sanitarInIDs != "''" || $this->getCFGDef('ignoreEmpty', '0')) {
            $from = $this->table . " " . $this->_filters['join'];
            $where = $this->getCFGDef('addWhereList', '');

            //====== block added by Dreamer ======
            $where = ($where ? $where . ' AND ' : '') . $this->_filters['where'];
            $where = sqlHelper::trimLogicalOp($where);
            //------- end of block -------

            if ($where != '') {
                $where = array($where);
            } else {
                $where = array();
            }
            if ($sanitarInIDs != "''") {
                if ($sanitarInIDs != "''") {
                    switch ($this->getCFGDef('idType', 'parents')) {
                        case 'parents':
                            switch ($this->getCFGDef('showParent', '0')) {
                                case '-1':
                                    $tmpWhere = "{$this->getParentField()} IN ({$sanitarInIDs})";
                                    break;
                                case 0:
                                    $tmpWhere = "{$this->getParentField()} IN ({$sanitarInIDs}) AND {$this->getPK()} NOT IN({$sanitarInIDs})";
                                    break;
                                case 1:
                                default:
                                    $tmpWhere = "({$this->getParentField()} IN ({$sanitarInIDs}) OR {$this->getPK()} IN({$sanitarInIDs}))";
                                    break;
                            }
                            if (($addDocs = $this->getCFGDef('documents', '')) != '') {
                                $addDocs = $this->sanitarIn($this->cleanIDs($addDocs));
                                $where[] = "((" . $tmpWhere . ") OR {$this->getPK()} IN({$addDocs}))";
                            } else {
                                $where[] = $tmpWhere;
                            }
                            break;
                        case 'documents':
                            $where[] = "{$this->getPK()} IN({$sanitarInIDs})";
                            break;
                    }
                }
            }
            if (!empty($where)) {
                $where = "WHERE " . implode(" AND ", $where);
            } else {
                $where = '';
            }

            $group = $this->getGroupSQL($this->getCFGDef('groupBy', $this->getPK()));
            $maxDocs = $this->getCFGDef('maxDocs', 0);
            $limit = $maxDocs > 0 ? $this->LimitSQL($this->getCFGDef('maxDocs', 0)) : '';
            $rs = ("SELECT count(*) FROM (SELECT count(*) FROM {$from} {$where} {$group} {$limit}) as `tmp`");
            $out = $this->modx->db->getValue($rs);
        }

        return $out;
    }

    /**
     * @param string|array $id
     * @return array
     */
    public function getChildrenFolder($id)
    {
        $out = array();
        $sanitarInIDs = $this->sanitarIn($id);

        $tmp = $this->getCFGDef('addWhereFolder', '');
        $where = "{$this->getParentField()} IN ({$sanitarInIDs})";
        if (!empty($tmp)) {
            $where .= " AND " . $tmp;
        }

        $rs = $this->dbQuery("SELECT {$this->getPK()} FROM {$this->table} WHERE {$where}");
        $pk = $this->getPK(false);
        while ($item = $this->modx->db->getRow($rs)) {
            $out[] = $item[$pk];
        }

        return $out;
    }
}
