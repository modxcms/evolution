<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

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
    protected $table = 'site_content';

    /**
     * @var string
     */
    protected $idField = 'id';

    /**
     * @var string
     */
    protected $parentField = 'parent';

    /**
     * @absctract
     */
    public function getDocs($tvlist = '')
    {
        if ($this->checkExtender('paginate')) {
            $this->extender['paginate']->init($this);
        } else {
            $this->config->setConfig(array('start' => 0));
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

                    $item[$this->getCFGDef("sysKey", "dl") . '.summary'] = $extSummary ? $this->getSummary($item,
                        $extSummary) : '';

                    $item = array_merge($item,
                        $sysPlh); //inside the chunks available all placeholders set via $modx->toPlaceholders with prefix id, and with prefix sysKey
                    $item[$this->getCFGDef("sysKey",
                        "dl") . '.iteration'] = $i; //[+iteration+] - Number element. Starting from zero

                    $date = $this->getCFGDef('dateSource', 'pub_date');
                    $date = isset($item[$date]) ? $item[$date] + $this->modx->config['server_offset_time'] : '';
                    if ($date != '' && $this->getCFGDef('dateFormat', '%d.%b.%y %H:%M') != '') {
                        $item[$this->getCFGDef("sysKey", "dl") . '.date'] = strftime($this->getCFGDef('dateFormat',
                            '%d.%b.%y %H:%M'), $date);
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
                        $this->toPlaceholders($tmp, 1,
                            "item[" . $i . "]"); // [+item[x]+] – individual placeholder for each iteration documents on this page
                    }
                    $out .= $tmp;
                    $i++;
                }
            }
            $out = $this->renderWrap($out);
        } else {
            $out = 'none TPL';
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

        foreach ($data as $num => $item) {
            $row = $item;

            switch (true) {
                case ((array('1') == $fields || in_array('summary', $fields)) && $extSummary):
                    $row['summary'] = $this->getSummary($this->_docs[$num], $extSummary, 'introtext');
                //without break
                case ((array('1') == $fields || in_array('date', $fields)) && $date != 'date'):
                    $tmp = (isset($this->_docs[$num][$date]) && $date != 'createdon' && $this->_docs[$num][$date] != 0 && $this->_docs[$num][$date] == (int)$this->_docs[$num][$date]) ? $this->_docs[$num][$date] : $this->_docs[$num]['createdon'];
                    $row['date'] = strftime($this->getCFGDef('dateFormat', '%d.%b.%y %H:%M'),
                        $tmp + $this->modx->config['server_offset_time']);
                // no break
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
            $where = $this->getCFGDef('addWhereList', '');
            if ($where != '') {
                $where = array($where);
            }
            if ($sanitarInIDs != "''") {
                $where[] = "`{$this->getPK()}` IN ({$sanitarInIDs})";
            }

            if (!empty($where)) {
                $where = "WHERE " . implode(" AND ", $where);
            }
            $limit = $this->LimitSQL($this->getCFGDef('queryLimit', 0));
            $fields = $this->getCFGDef('selectFields', '*');
            $group = $this->getGroupSQL($this->getCFGDef('groupBy', ''));
            $rs = $this->dbQuery("SELECT {$fields} FROM {$this->table} {$where} {$group} {$this->SortOrderSQL($this->getPK())} {$limit}");

            $rows = $this->modx->db->makeArray($rs);
            $out = array();
            foreach ($rows as $item) {
                $out[$item[$this->getPK()]] = $item;
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

        $tmpWhere = $this->getCFGDef('addWhereList', '');
        $tmpWhere = sqlHelper::trimLogicalOp($tmpWhere);
        if (!empty($tmpWhere)) {
            $where[] = $tmpWhere;
        }
        $sanitarInIDs = $this->sanitarIn($this->IDs);

        $tmpWhere = null;
        if ($sanitarInIDs != "''") {
            $tmpWhere = "(`{$this->getParentField()}` IN (" . $sanitarInIDs . ")";
            switch ($this->getCFGDef('showParent', '0')) {
                case -1:
                    $tmpWhere .= ")";
                    break;
                case 0:
                    $tmpWhere .= " AND `{$this->getPK()}` NOT IN(" . $sanitarInIDs . "))";
                    break;
                case 1:
                default:
                    $tmpWhere .= " OR `{$this->getPK()}` IN({$sanitarInIDs}))";
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
        $group = $this->getGroupSQL($this->getCFGDef('groupBy', "`{$this->getPK()}`"));
        if ($sanitarInIDs != "''" || $this->getCFGDef('ignoreEmpty', '0')) {
            $sql = $this->dbQuery("SELECT {$fields} FROM " . $this->table . " " . $where . " " .
                $group . " " .
                $this->SortOrderSQL($this->getPK()) . " " .
                $this->LimitSQL($this->getCFGDef('queryLimit', 0))
            );
            $rows = $this->modx->db->makeArray($sql);
            foreach ($rows as $item) {
                $out[$item[$this->getPK()]] = $item;
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
            $where = $this->getCFGDef('addWhereList', '');
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
                                    $tmpWhere = "`{$this->getParentField()}` IN ({$sanitarInIDs})";
                                    break;
                                case 0:
                                    $tmpWhere = "`{$this->getParentField()}` IN ({$sanitarInIDs}) AND `{$this->getPK()}` NOT IN({$sanitarInIDs})";
                                    break;
                                case 1:
                                default:
                                    $tmpWhere = "(`{$this->getParentField()}` IN ({$sanitarInIDs}) OR `{$this->getPK()}` IN({$sanitarInIDs}))";
                                    break;
                            }
                            if (($addDocs = $this->getCFGDef('documents', '')) != '') {
                                $addDocs = $this->sanitarIn($this->cleanIDs($addDocs));
                                $where[] = "((" . $tmpWhere . ") OR `{$this->getPK()}` IN({$addDocs}))";
                            } else {
                                $where[] = $tmpWhere;
                            }
                            break;
                        case 'documents':
                            $where[] = "`{$this->getPK()}` IN({$sanitarInIDs})";
                            break;
                    }
                }
            }
            if (!empty($where)) {
                $where = "WHERE " . implode(" AND ", $where);
            } else {
                $where = '';
            }

            $group = $this->getGroupSQL($this->getCFGDef('groupBy', "`{$this->getPK()}`"));
            $rs = $this->dbQuery("SELECT count(*) FROM (SELECT count(*) FROM {$this->table} {$where} {$group}) as `tmp`");

            $out = $this->modx->db->getValue($rs);
        }

        return $out;
    }

    /**
     * @param string $id
     * @return array
     */
    public function getChildrenFolder($id)
    {
        $sanitarInIDs = $this->sanitarIn($id);

        $tmp = $this->getCFGDef('addWhereFolder', '');
        $where = "`{$this->getParentField()}` IN ({$sanitarInIDs})";
        if (!empty($tmp)) {
            $where .= " AND " . $tmp;
        }

        $rs = $this->dbQuery("SELECT `{$this->getPK()}` FROM {$this->table} WHERE {$where}");

        $rows = $this->modx->db->makeArray($rs);
        $out = array();
        foreach ($rows as $item) {
            $out[] = $item[$this->getPK()];
        }

        return $out;
    }
}
