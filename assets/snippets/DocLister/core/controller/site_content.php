<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

/**
 * site_content controller
 * @see http://modx.im/blog/addons/374.html
 *
 * @category controller
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>, kabachello <kabachnik@hotmail.com>
 */
class site_contentDocLister extends DocLister
{
    /**
     * Экземпляр экстендера TV
     *
     * @var null|xNop|tv_DL_Extender
     */
    protected $extTV = null;

    /**
     * Экземпляр экстендера пагинации
     * @var null|paginate_DL_Extender
     */
    protected $extPaginate = null;

    /**
     * site_contentDocLister constructor.
     * @param DocumentParser $modx
     * @param array $cfg
     * @param null $startTime
     */
    public function __construct($modx, $cfg = array(), $startTime = null)
    {
        parent::__construct($modx, $cfg, $startTime);
        $this->extTV = $this->getExtender('tv', true, true);
    }

    /**
     * @absctract
     */
    public function getDocs($tvlist = '')
    {
        if ($tvlist == '') {
            $tvlist = $this->getCFGDef('tvList', '');
        }

        $this->extTV->getAllTV_Name();

        if ($this->extPaginate = $this->getExtender('paginate')) {
            $this->extPaginate->init($this);
        } else {
            $this->config->setConfig(array('start' => 0));
        }
        $type = $this->getCFGDef('idType', 'parents');
        $this->_docs = ($type == 'parents') ? $this->getChildrenList() : $this->getDocList();
        if ($tvlist != '' && count($this->_docs) > 0) {
            $tv = $this->extTV->getTVList(array_keys($this->_docs), $tvlist);
            if (!is_array($tv)) {
                $tv = array();
            }
            foreach ($tv as $docID => $TVitem) {
                if (isset($this->_docs[$docID]) && is_array($this->_docs[$docID])) {
                    $this->_docs[$docID] = array_merge($this->_docs[$docID], $TVitem);
                } else {
                    unset($this->_docs[$docID]);
                }
            }
        }
        if (1 == $this->getCFGDef('tree', '0')) {
            $this->treeBuild('id', 'parent');
        }

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
            $tpl = $this->getCFGDef('tpl', '@CODE:<a href="[+url+]">[+pagetitle+]</a><br />');
        }
        if ($tpl != '') {
            $date = $this->getCFGDef('dateSource', 'pub_date');

            $this->toPlaceholders(count($this->_docs), 1, "display"); // [+display+] - сколько показано на странице.

            $i = 1;
            $sysPlh = $this->renameKeyArr($this->_plh, $this->getCFGDef("sysKey", "dl"));
            if (count($this->_docs) > 0) {
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

                /**
                 * @var $extJotCount jotcount_DL_Extender
                 */
                $extJotCount = $this->getCFGdef('jotcount', 0) ? $this->getExtender('jotcount', true) : null;

                if ($extJotCount) {
                    $comments = $extJotCount->countComments(array_keys($this->_docs));
                }

                $this->skippedDocs = 0;
                foreach ($this->_docs as $item) {
                    $this->renderTPL = $tpl;
                    if ($extUser) {
                        $item = $extUser->setUserData($item); //[+user.id.createdby+], [+user.fullname.publishedby+], [+dl.user.publishedby+]....
                    }

                    $item['summary'] = $extSummary ? $this->getSummary($item, $extSummary, 'introtext', 'content') : '';

                    if ($extJotCount) {
                        $item['jotcount'] = APIHelpers::getkey($comments, $item['id'], 0);
                    }

                    $item = array_merge($item,
                        $sysPlh); //inside the chunks available all placeholders set via $modx->toPlaceholders with prefix id, and with prefix sysKey
                    $item['iteration'] = $i; //[+iteration+] - Number element. Starting from zero

                    $item['title'] = ($item['menutitle'] == '' ? $item['pagetitle'] : $item['menutitle']);

                    if ($this->getCFGDef('makeUrl', 1)) {
                        if ($item['type'] == 'reference') {
                            $item['url'] = is_numeric($item['content']) ? $this->modx->makeUrl($item['content'], '', '',
                                $this->getCFGDef('urlScheme', '')) : $item['content'];
                        } else {
                            $item['url'] = $this->modx->makeUrl($item['id'], '', '', $this->getCFGDef('urlScheme', ''));
                        }
                    }

                    $item['date'] = (isset($item[$date]) && $date != 'createdon' && $item[$date] != 0 && $item[$date] == (int)$item[$date]) ? $item[$date] : $item['createdon'];
                    $item['date'] = $item['date'] + $this->modx->config['server_offset_time'];
                    if ($this->getCFGDef('dateFormat', '%d.%b.%y %H:%M') != '') {
                        $item['date'] = strftime($this->getCFGDef('dateFormat', '%d.%b.%y %H:%M'), $item['date']);
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
            } else {
                $noneTPL = $this->getCFGDef("noneTPL", "");
                $out = ($noneTPL != '') ? $this->parseChunk($noneTPL, $sysPlh) : '';
            }
            $out = $this->renderWrap($out);
        } else {
            $out = 'no template';
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
            if ((array('1') == $fields || in_array('summary', $fields)) && $extSummary) {
                $row['summary'] = $this->getSummary($this->_docs[$num], $extSummary, 'introtext', 'content');
            }
            if (array('1') == $fields || in_array('date', $fields)) {
                $tmp = (isset($this->_docs[$num][$date]) && $date != 'createdon' && $this->_docs[$num][$date] != 0 && $this->_docs[$num][$date] == (int)$this->_docs[$num][$date]) ? $this->_docs[$num][$date] : $this->_docs[$num]['createdon'];
                $row['date'] = strftime($this->getCFGDef('dateFormat', '%d.%b.%y %H:%M'),
                    $tmp + $this->modx->config['server_offset_time']);
            }
            if (array('1') == $fields || in_array(array('menutitle', 'pagetitle'), $fields)) {
                $row['title'] = ($row['menutitle'] == '' ? $row['pagetitle'] : $row['menutitle']);
            }
            if ((bool)$this->getCFGDef('makeUrl', 1) && (array('1') == $fields || in_array(array('content', 'type'),
                        $fields))
            ) {
                if ($row['type'] == 'reference') {
                    $row['url'] = is_numeric($row['content']) ? $this->modx->makeUrl($row['content'], '', '',
                        $this->getCFGDef('urlScheme', '')) : $row['content'];
                } else {
                    $row['url'] = $this->modx->makeUrl($row['id'], '', '', $this->getCFGDef('urlScheme', ''));
                }
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
     * @abstract
     */
    public function getChildrenCount()
    {
        $out = 0;
        $sanitarInIDs = $this->sanitarIn($this->IDs);
        if ($sanitarInIDs != "''" || $this->getCFGDef('ignoreEmpty', '0')) {
            $where = $this->getCFGDef('addWhereList', '');
            $where = sqlHelper::trimLogicalOp($where);
            $where = ($where ? $where . ' AND ' : '') . $this->_filters['where'];
            if ($where != '' && $this->_filters['where'] != '') {
                $where .= " AND ";
            }
            $where = sqlHelper::trimLogicalOp($where);

            $where = "WHERE {$where}";
            $whereArr = array();
            if (!$this->getCFGDef('showNoPublish', 0)) {
                $whereArr[] = "c.deleted=0 AND c.published=1";
            }

            $tbl_site_content = $this->getTable('site_content', 'c');

            if ($sanitarInIDs != "''") {
                switch ($this->getCFGDef('idType', 'parents')) {
                    case 'parents':
                        switch ($this->getCFGDef('showParent', '0')) {
                            case '-1':
                                $tmpWhere = "c.parent IN (" . $sanitarInIDs . ")";
                                break;
                            case 0:
                                $tmpWhere = "c.parent IN ({$sanitarInIDs}) AND c.id NOT IN({$sanitarInIDs})";
                                break;
                            case 1:
                            default:
                                $tmpWhere = "(c.parent IN ({$sanitarInIDs}) OR c.id IN({$sanitarInIDs}))";
                                break;
                        }
                        if (($addDocs = $this->getCFGDef('documents', '')) != '') {
                            $addDocs = $this->sanitarIn($this->cleanIDs($addDocs));
                            $whereArr[] = "((" . $tmpWhere . ") OR c.id IN({$addDocs}))";
                        } else {
                            $whereArr[] = $tmpWhere;
                        }

                        break;
                    case 'documents':
                        $whereArr[] = "c.id IN({$sanitarInIDs})";
                        break;
                }
            }
            $from = $tbl_site_content . " " . $this->_filters['join'];
            $where = sqlHelper::trimLogicalOp($where);

            if (trim($where) != 'WHERE') {
                $where .= " AND ";
            }

            $where .= implode(" AND ", $whereArr);
            $where = sqlHelper::trimLogicalOp($where);

            if (trim($where) == 'WHERE') {
                $where = '';
            }
            $group = $this->getGroupSQL($this->getCFGDef('groupBy', 'c.id'));
            $sort = $this->SortOrderSQL("if(c.pub_date=0,c.createdon,c.pub_date)");
            list($from) = $this->injectSortByTV($from, $sort);

            $rs = $this->dbQuery("SELECT count(*) FROM (SELECT count(*) FROM {$from} {$where} {$group}) as `tmp`");
            $out = $this->modx->db->getValue($rs);
        }

        return $out;
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
            $where = sqlHelper::trimLogicalOp($where);

            $where = ($where ? $where . ' AND ' : '') . $this->_filters['where'];
            $where = sqlHelper::trimLogicalOp($where);

            $tbl_site_content = $this->getTable('site_content', 'c');
            if ($sanitarInIDs != "''") {
                $where .= ($where ? " AND " : "") . "c.id IN ({$sanitarInIDs}) AND";
            }
            $where = sqlHelper::trimLogicalOp($where);

            if ($this->getCFGDef('showNoPublish', 0)) {
                if ($where != '') {
                    $where = "WHERE {$where}";
                } else {
                    $where = '';
                }
            } else {
                if ($where != '') {
                    $where = "WHERE {$where} AND ";
                } else {
                    $where = "WHERE {$where} ";
                }
                $where .= "c.deleted=0 AND c.published=1";
            }


            $fields = $this->getCFGDef('selectFields', 'c.*');
            $group = $this->getGroupSQL($this->getCFGDef('groupBy', 'c.id'));
            $sort = $this->SortOrderSQL("if(c.pub_date=0,c.createdon,c.pub_date)");
            list($tbl_site_content, $sort) = $this->injectSortByTV($tbl_site_content . ' ' . $this->_filters['join'],
                $sort);

            $limit = $this->LimitSQL($this->getCFGDef('queryLimit', 0));

            $rs = $this->dbQuery("SELECT {$fields} FROM {$tbl_site_content} {$where} {$group} {$sort} {$limit}");

            $rows = $this->modx->db->makeArray($rs);

            foreach ($rows as $item) {
                $out[$item['id']] = $item;
            }
        }

        return $out;
    }

    /**
     * @param string $id
     * @return array
     */
    public function getChildrenFolder($id)
    {
        $where = $this->getCFGDef('addWhereFolder', '');
        $where = sqlHelper::trimLogicalOp($where);
        if ($where != '') {
            $where .= " AND ";
        }

        $tbl_site_content = $this->getTable('site_content', 'c');
        $sanitarInIDs = $this->sanitarIn($id);
        if ($this->getCFGDef('showNoPublish', 0)) {
            $where = "WHERE {$where} c.parent IN ({$sanitarInIDs}) AND c.isfolder=1";
        } else {
            $where = "WHERE {$where} c.parent IN ({$sanitarInIDs}) AND c.deleted=0 AND c.published=1 AND c.isfolder=1";
        }

        $rs = $this->dbQuery("SELECT id FROM {$tbl_site_content} {$where}");

        $rows = $this->modx->db->makeArray($rs);
        $out = array();
        foreach ($rows as $item) {
            $out[] = $item['id'];
        }

        return $out;
    }

    /**
     * @param $table
     * @param $sort
     * @return array
     */
    protected function injectSortByTV($table, $sort)
    {
        $out = $this->getExtender('tv', true, true)->injectSortByTV($table, $sort);
        if (!is_array($out) || empty($out)) {
            $out = array($table, $sort);
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

        $tmpWhere = sqlHelper::trimLogicalOp($this->_filters['where']);
        if (!empty($tmpWhere)) {
            $where[] = $tmpWhere;
        }

        $tbl_site_content = $this->getTable('site_content', 'c');

        $sort = $this->SortOrderSQL("if(c.pub_date=0,c.createdon,c.pub_date)");
        list($from, $sort) = $this->injectSortByTV($tbl_site_content . ' ' . $this->_filters['join'], $sort);
        $sanitarInIDs = $this->sanitarIn($this->IDs);

        $tmpWhere = null;
        if ($sanitarInIDs != "''") {
            switch ($this->getCFGDef('showParent', '0')) {
                case '-1':
                    $tmpWhere = "c.parent IN (" . $sanitarInIDs . ")";
                    break;
                case 0:
                    $tmpWhere = "c.parent IN (" . $sanitarInIDs . ") AND c.id NOT IN(" . $sanitarInIDs . ")";
                    break;
                case 1:
                default:
                    $tmpWhere = "(c.parent IN (" . $sanitarInIDs . ") OR c.id IN({$sanitarInIDs}))";
                    break;
            }
        }
        if (($addDocs = $this->getCFGDef('documents', '')) != '') {
            $addDocs = $this->sanitarIn($this->cleanIDs($addDocs));
            if (empty($tmpWhere)) {
                $tmpWhere = "c.id IN({$addDocs})";
            } else {
                $tmpWhere = "((" . $tmpWhere . ") OR c.id IN({$addDocs}))";
            }
        }
        if (!empty($tmpWhere)) {
            $where[] = $tmpWhere;
        }
        if (!$this->getCFGDef('showNoPublish', 0)) {
            $where[] = "c.deleted=0 AND c.published=1";
        }
        if (!empty($where)) {
            $where = "WHERE " . implode(" AND ", $where);
        } else {
            $where = '';
        }
        $fields = $this->getCFGDef('selectFields', 'c.*');
        $group = $this->getGroupSQL($this->getCFGDef('groupBy', 'c.id'));

        if ($sanitarInIDs != "''" || $this->getCFGDef('ignoreEmpty', '0')) {
            $sql = $this->dbQuery("SELECT {$fields} FROM " . $from . " " . $where . " " .
                $group . " " .
                $sort . " " .
                $this->LimitSQL($this->getCFGDef('queryLimit', 0))
            );

            $rows = $this->modx->db->makeArray($sql);

            foreach ($rows as $item) {
                $out[$item['id']] = $item;
            }
        }

        return $out;
    }

    /**
     * @param string $field
     * @param string $type
     * @return string
     */
    public function changeSortType($field, $type)
    {
        $type = trim($type);
        switch (strtoupper($type)) {
            case 'TVDATETIME':
                $field = "STR_TO_DATE(" . $field . ",'%d-%m-%Y %H:%i:%s')";
                break;
            default:
                $field = parent::changeSortType($field, $type);
        }

        return $field;
    }
}
