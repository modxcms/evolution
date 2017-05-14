<?php

if (!class_exists("DLFixedPrepare", false)) {
    /**
     * Class DLFixedPrepare
     */
    class DLFixedPrepare
    {
        /**
         * @param array $data
         * @param DocumentParser $modx
         * @param $_DL
         * @param prepare_DL_Extender $_eDL
         * @return array
         */
        public static function buildMenu(array $data = array(), DocumentParser $modx, $_DL, prepare_DL_Extender $_eDL)
        {
            $params = $_DL->getCFGDef('params', array());
            if ($_DL->getCfgDef('currentDepth', 1) < $_DL->getCFGDef('maxDepth', 5)) {
                $params['currentDepth'] = $_DL->getCfgDef('currentDepth', 1) + 1;
                $params['parents'] = $data['id'];
                $params['idType'] = 'parents';
                $params['documents'] = '';
                $data['dl.submenu'] = ($data['isfolder']) ? $modx->runSnippet('DLBuildMenu', $params) : '';
            } else {
                $data['dl.submenu'] = '';
            }
            $data['dl.currentDepth'] = $_DL->getCfgDef('currentDepth', 1);

            if (($parentIDs = $_eDL->getStore('parentIDs')) === null) {
                $parentIDs = array_values($modx->getParentIds($modx->documentObject['id']));
                $_eDL->setStore('parentIDs', $parentIDs);
            }
            $isActive = ((is_array($parentIDs) && in_array($data['id'],
                        $parentIDs)) || $data['id'] == $modx->documentObject['id']);
            $activeClass = $_DL->getCfgDef('activeClass', 'active');
            if ($isActive) {
                $data['dl.class'] .= ' ' . $activeClass;
            }

            if (strpos($data['dl.class'], 'current') !== false && strpos($data['dl.class'],
                    ' ' . $activeClass) === false
            ) {
                $data['dl.class'] = str_replace('current', 'current ' . $activeClass, $data['dl.class']);
            }

            $tpl = empty($data['dl.submenu']) ? 'noChildrenRowTPL' : 'mainRowTpl';

            $_DL->renderTPL = $_DL->getCfgDef($tpl);
            if (strpos($data['dl.class'], 'current') !== false) {
                $_DL->renderTPL = $_DL->getCfgDef('TplCurrent', $_DL->renderTPL);
                $_DL->renderTPL = $_DL->getCfgDef('TplCurrent' . $data['dl.currentDepth'], $_DL->renderTPL);
                if (empty($data['dl.submenu'])) {
                    $_DL->renderTPL = $_DL->getCfgDef('TplCurrentNoChildren' . $data['dl.currentDepth'],
                        $_DL->renderTPL);
                }
            }

            return $data;
        }

        /**
         * @param array $data
         * @param DocumentParser $modx
         * @param DocLister $_DocLister
         * @param prepare_DL_Extender $_extDocLister
         * @return array
         */
        public static function firstChar(
            array $data = array(),
            DocumentParser $modx,
            DocLister $_DocLister,
            prepare_DL_Extender $_extDocLister
        ) {
            $char = mb_substr($data['pagetitle'], 0, 1, 'UTF-8');
            $oldChar = $_extDocLister->getStore('char');
            if ($oldChar !== $char) {
                $sanitarInIDs = $_DocLister->sanitarIn($_DocLister->getIDs());
                $where = sqlHelper::trimLogicalOp($_DocLister->getCFGDef('addWhereList', ''));
                $where = sqlHelper::trimLogicalOp(($where ? $where . ' AND ' : '') . $_DocLister->filtersWhere());
                $where = sqlHelper::trimLogicalOp(($where ? $where . ' AND ' : '') . "SUBSTRING(c.pagetitle,1,1) = '" . $modx->db->escape($char) . "'");

                if ($_DocLister->getCFGDef('idType', 'parents') == 'parents') {

                    if ($where != '') {
                        $where .= " AND ";
                    }
                    $where = "WHERE {$where} c.parent IN (" . $sanitarInIDs . ")";
                    if (!$_DocLister->getCFGDef('showNoPublish', 0)) {
                        $where .= " AND c.deleted=0 AND c.published=1";
                    }
                } else {
                    if ($sanitarInIDs != "''") {
                        $where .= ($where ? " AND " : "") . "c.id IN ({$sanitarInIDs}) AND";
                    }
                    $where = sqlHelper::trimLogicalOp($where);
                    if ($_DocLister->getCFGDef('showNoPublish', 0)) {
                        if ($where != '') {
                            $where = "WHERE {$where}";
                        }
                    } else {
                        if ($where != '') {
                            $where = "WHERE {$where} AND ";
                        } else {
                            $where = "WHERE {$where} ";
                        }
                        $where .= "c.deleted=0 AND c.published=1";
                    }
                }
                $q = $_DocLister->dbQuery("SELECT count(c.id) as total FROM " . $_DocLister->getTable('site_content',
                        'c') . " " . $where);
                $total = $modx->db->getValue($q);
                $data['OnNewChar'] = $_DocLister->parseChunk($_DocLister->getCFGDef('tplOnNewChar'),
                    compact("char", "total"));
                $_extDocLister->setStore('char', $char);

                if ($oldChar !== null) {
                    $data['CharSeparator'] = $_DocLister->parseChunk($_DocLister->getCFGDef('tplCharSeparator'),
                        compact("char", "total"));
                }
            }

            return $data;
        }
    }
}
