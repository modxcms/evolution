<?php
include_once('site_content.php');

/**
 * site_content controller
 * @see http://modx.im/blog/addons/374.html
 *
 * @category controller
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>, kabachello <kabachnik@hotmail.com>
 */
class site_content_menuDocLister extends site_contentDocLister
{
    public $levels = array();
    protected $currentLevel = 1;
    protected $docTvs = array();
    protected $IDs = array();
    protected $activeBranch = array();
    protected $countChildren = array();

    /**
     * Очистка массива $IDs по которому потом будет производиться выборка документов
     *
     * @param mixed $IDs список id документов по которым необходима выборка
     * @return array очищенный массив
     */
    public function setIDs($IDs)
    {
        $this->debug->debug('set ID list ' . $this->debug->dumpData($IDs), 'setIDs', 2);
        $IDs = $this->cleanIDs($IDs);
        $this->debug->debugEnd("setIDs");

        return ($this->IDs = $IDs);
    }

    /**
     * @param string $tvlist
     * @return array
     */
    public function getDocs($tvlist = '')
    {
        $maxDepth = $this->getCFGDef('maxDepth', 10);
        //TODO кэширование
        if ($this->getCFGDef('hideSubMenus', 0) && empty($this->getCFGDef('openIds'))) {
            $maxDepth = $this->setActiveBranch($this->getHereId());
        } else {
            $this->setActiveBranch($this->getHereId());
        }
        if ($oIds = $this->getCFGDef('openIds')) {
            $maxDepth = 1;
            $oIds = $this->cleanIDs($oIds);
            $oIds[] = $this->getHereId();
            foreach ($oIds as $id) {
                if (($c = $this->setActiveBranch($id)) > $maxDepth) {
                    $maxDepth = $c;
                }
            }
            $this->config->setConfig(array('hideSubMenus' => 1));
        }
        $currentLevel = &$this->currentLevel;
        $currentLevel = 1;
        if ($this->getCFGDef('showParent', 0) && in_array(0, $this->IDs)) {
            $this->config->setConfig(array('showParent' => 0));
        }
        if ($this->getCFGDef('showParent', 0)) {
            $orderBy = $this->getCFGDef('orderBy');
            $docs = $this->getDocList();
            $this->config->setConfig(array('orderBy' => $orderBy));
            $this->levels[$currentLevel++] = $docs;
            $this->IDs = $ids = array_keys($docs);
            $this->config->setConfig(array('showParent' => 0));
        }
        while ($currentLevel <= $maxDepth) {
            $orderBy = $this->getCFGDef('orderBy');
            $docs = $this->getChildrenList();
            $this->config->setConfig(array('orderBy' => $orderBy));
            if (empty($docs)) {
                break;
            }
            $this->levels[$currentLevel++] = $docs;
            $this->IDs = array_keys($docs);
        }

        if ($tvlist == '') {
            $tvlist = $this->getCFGDef('tvList', '');
        }

        if ($tvlist != '') {
            $this->extTV->getAllTV_Name();
            $ids = array();
            foreach ($this->levels as $level => $docs) {
                $ids = array_merge($ids, array_keys($docs));
            }
            if ($ids) {
                $tv = $this->extTV->getTVList($ids, $tvlist);
                if (!is_array($tv)) {
                    $tv = array();
                }
                $this->docTvs = $tv;
            }

        }
        if ($this->getCFGDef('countChildren', 0)) {
            $this->countChildren();
        }

        return $this->levels;
    }

    /**
     * Список активных документов
     * @param $id
     * @param int $maxDepth
     */
    public function setActiveBranch($id, $maxDepth = 10)
    {
        $ids = array_values($this->modx->getParentIds($id, $maxDepth));
        $ids[] = $id;
        $ids[] = 0;
        $this->activeBranch = array_merge($this->activeBranch, $ids);
        $this->activeBranch = array_keys(array_flip($this->activeBranch));

        return count($ids);
    }

    /**
     * Подсчет количества непосредственных дочерних документов
     */
    public function countChildren()
    {
        $ids = array();
        $out = &$this->countChildren;
        foreach ($this->levels as $level => $docs) {
            $ids = array_merge($ids, array_keys($docs));
        }
        $maxDepth = count($this->levels);
        $currentDepth = 1;
        while ($currentDepth <= $maxDepth) {
            $_ids = implode(',', $ids);
            if (empty($_ids)) {
                break;
            }
            $q = $this->dbQuery("SELECT `parent`,COUNT(*) as `count` FROM {$this->getTable('site_content')} WHERE `parent` IN ({$_ids}) AND `published`=1 AND `deleted`=0 GROUP BY `parent`");
            $_ids = array();
            while ($row = $this->modx->db->getRow($q)) {
                $_ids[] = $row['parent'];
                $out[$row['parent']] = $row['count'];
            }
            if ($_ids) {
                $ids = $this->diff($ids, $_ids);
            } else {
                break;
            }
            $currentDepth++;
        }
    }

    /**
     * Возвращает элементы массива $a, которых нет в массиве $b
     * @param $b
     * @param $a
     * @return array
     */
    private function diff($b, $a)
    {
        $at = array_flip($a);
        $d = array();
        foreach ($b as $i) {
            if (!isset($at[$i])) {
                $d[] = $i;
            }
        }

        return $d;
    }


    /**
     * Подготовка результатов к отображению в соответствии с настройками
     *
     * @param string $tpl шаблон
     * @return string
     */
    public function render($tpl = '')
    {
        $this->debug->debug(array('Render data with template ' => $tpl), 'render', 2, array('html'));
        $out = $this->_render($tpl);

        if ($out) {
            $this->outData = DLTemplate::getInstance($this->modx)->parseDocumentSource($out);
        }
        $this->debug->debugEnd('render');

        return $this->outData;
    }

    /**
     * @param string $tpl
     * @return string
     */
    public function _render($tpl = '')
    {
        $currentLevel = &$this->currentLevel;
        $currentLevel = count($this->levels);
        $docs = $this->levels;
        /** @var prepare_DL_Extender_ $extPrepare */
        $extPrepare = $this->getExtender('prepare');

        while ($currentLevel > 0) {
            foreach ($docs[$currentLevel] as $id => &$data) {
                if ($out = $this->prepareData($data)) {
                    if (is_array($out)) {
                        $data = $out;
                    }
                };

                if (!isset($data['maxLevel'])) {
                    $data['maxLevel'] = 1;
                    $docs[$currentLevel - 1][$data[$this->parentField]]['maxLevel'] = 0;
                }

                if (isset($data['here']) || isset($data['active'])) {
                    $docs[$currentLevel - 1][$data[$this->parentField]]['active'] = 1;
                }

                if ($extPrepare) {
                    $data = $extPrepare->init($this, array(
                        'data'      => $data,
                        'nameParam' => 'prepare'
                    ));
                    if (is_bool($data) && $data === false) {
                        continue;
                    }
                }

                if (isset($data['wrap'])) {
                    $data['wrap'] = is_array($data['wrap']) ? $this->parseRow($data['wrap']) : $data['wrap'];
                    $data['wrap'] = $this->parseOuter($data);
                }
                $hideSubMenus = $this->getCFGDef('hideSubMenus', 0);
                $hideSubMenus = !$hideSubMenus || ($hideSubMenus && in_array((int)$data[$this->parentField],
                            $this->activeBranch));
                if ($hideSubMenus) {
                    $docs[$currentLevel - 1][$data[$this->parentField]]['wrap'][] = $data;
                }
            }
            unset($docs[$currentLevel]);
            $currentLevel--;
        }
        unset($data);
        $out = '';
        $joinMenus = $this->getCFGDef('joinMenus',0) && !$this->getCFGDef('showParents',0);
        foreach ($docs[0] as $id => $data) {
            if (isset($data['wrap'])) {
                if ($joinMenus) {
                    $out .= $this->parseRow($data['wrap']);
                } else {
                    $data['wrap'] = $this->parseRow($data['wrap']);
                    $out .= $this->parseOuter($data);
                }
            }
        }
        if ($joinMenus) $out = $this->parseOuter(array('wrap'=>$out));

        return $out;
    }

    /**
     * Добавление виртуальных плейсхолдеров
     * @param $data
     * @return array
     */
    public function prepareData($data)
    {
        /**
         * @var e_DL_Extender $extE
         */
        $extE = $this->getExtender('e', true, true);
        $id = $data['id'];
        if (isset($this->docTvs[$id])) {
            $data = array_merge($data, $this->docTvs[$id]);
        }
        if ($id == $this->getHereId()) {
            $data['here'] = 1;
        }
        if (in_array($id,$this->activeBranch)) {
            $data['active'] = 1;
        }
        if ($this->getCFGDef('hideSubMenus') && isset($data['isfolder']) && $data['isfolder']) {
            $data['state'] = in_array($data['id'], $this->activeBranch) ? 'open' : 'closed';
        }

        $titleField = $this->getCFGDef('titleField', 'title');
        $data[$titleField] = isset($data['menutitle']) && !empty($data['menutitle']) ? $data['menutitle'] : $data['pagetitle'];
        $data['level'] = $this->currentLevel;
        $data['url'] = $this->makeUrl($data);
        if ($this->getCFGDef('countChildren', 1)) {
            $data['count'] = isset($this->countChildren[$data['id']]) ? $this->countChildren[$data['id']] : 0;
        }

        if ($out = $extE->init($this, compact('data'))) {
            if (is_array($out)) {
                $data = $out;
            }
        }

        return $data;
    }

    /**
     * Вывод обертки блока меню
     * @param array $data
     * @return string
     */
    public function parseOuter($data = array())
    {
        $tpl = $this->getCFGDef('outerTpl', '@CODE:<ul[+classes+]>[+wrap+]</ul>');
        $classes = '';
        $classNames = $this->getCFGDef('outerClass');
        if ($this->currentLevel >= 1) {
            $tpl = $this->getCFGDef('innerTpl', $tpl);
            $classNames = $this->getCFGDef('innerClass');
        }
        if ($classNames) {
            $classes = " class=\"{$classNames}\"";
        }
        $tpl = isset($data['_renderOuterTpl']) ? $data['_renderOuterTpl'] : $tpl;
        $out = $this->parseChunk($tpl,
            array_merge($data,
                array('classes' => $classes, 'classNames' => $classNames)));

        return $out;
    }

    /**
     * Вывод пункта меню
     * @param array $data
     * @return string
     */
    public function parseRow($data = array())
    {
        $out = '';
        $maxIteration = count($data) - 1;
        foreach ($data as $iteration => $item) {
            $item['iteration'] = $iteration + 1;
            if ($iteration == 0) {
                $item['first'] = 1;
            }
            if ($iteration == $maxIteration) {
                $item['last'] = 1;
            }
            $tpl = isset($item['_renderRowTpl']) ? $item['_renderRowTpl'] : $this->getRowTemplate($item);
            $item = array_merge($item, $this->getClasses($item));
            $out .= $this->parseChunk($tpl, $item);
        }

        return $out;
    }

    /**
     * Формирование ссылки на документ
     * @param array $data
     * @return string
     */
    protected function makeUrl($data = array())
    {
        $out = '';
        if ($this->getCFGDef('makeUrl', 1)) {
            if (isset($data['type']) && $data['type'] == 'reference' && isset($data['content'])) {
                $out = is_numeric($data['content']) ? $this->modx->makeUrl($data['content'], '', '',
                    $this->getCFGDef('urlScheme', '')) : $data['content'];
            } else {
                $out = isset($data['id']) && is_numeric($data['id']) ? $this->modx->makeUrl($data['id'], '', '',
                    $this->getCFGDef('urlScheme', '')) : '';
            }
        }

        return $out;
    }

    /**
     * Вовзращает id текущего документа
     * @return int
     */
    public function getHereId()
    {
        if (!$hereId = (int)$this->getCFGDef('hereId')) {
            $hereId = isset($this->modx->documentIdentifier) ? (int)$this->modx->documentIdentifier : 0;
        }

        return $hereId;
    }

    /**
     * Задание классов пункту меню
     * @param array $data
     * @return array
     */
    protected function getClasses($data = array())
    {
        $classes = isset($data['classes']) ? $data['classes'] : array(
            'rowClass'     => '',
            'firstClass'   => '',
            'lastClass'    => '',
            'levelClass'   => '',
            'webLinkClass' => '',
            'parentClass'  => '',
            'hereClass'    => '',
            'activeClass'  => '',
            'oddClass'     => '',
            'evenClass'    => ''
        );
        if (isset($data['state'])) {
            $classes['stateClass'] = $this->getCFGDef($data['state'] . 'Class', $data['state']);
        }
        if (isset($data['here'])) {
            $classes['hereClass'] = $this->getCFGDef('hereClass', 'current');
        }
        if (isset($data['active'])) {
            $classes['activeClass'] = $this->getCFGDef('activeClass', 'active');
        }
        $classes['rowClass'] = $this->getCFGDef('rowClass');
        if ($data['iteration'] % 2 == 1) {
            $classes['oddClass'] = $this->getCFGDef('oddClass', 'odd');
        } else {
            $classes['evenClass'] = $this->getCFGDef('evenClass', 'even');
        }
        if (isset($data['first'])) {
            $classes['firstClass'] = $this->getCFGDef('firstClass', 'first');
        }
        if (isset($data['last'])) {
            $classes['lastClass'] = $this->getCFGDef('lastClass', 'last');
        }
        if ($levelClass = $this->getCFGDef('levelClass', 'level')) {
            $classes['levelClass'] = $levelClass . $data['level'];
        }
        if (isset($data['type']) && $data['type'] == 'reference') {
            $classes['webLinkClass'] = $this->getCFGDef('webLinkClass');
        }
        if (!empty($data['wrap'])) {
            $classes['parentClass'] = $this->getCFGDef('parentClass');
        }
        $classNames = implode(' ', array_filter(array_values($classes)));
        $classes['classNames'] = $classNames;
        $classes['classes'] = " class=\"{$classNames}\"";

        return $classes;
    }

    /**
     * Вывод пункта меню
     * @param array $data
     * @return string
     */
    protected function getRowTemplate($data = array())
    {
        $tpl = $this->getCFGDef('rowTpl', '@CODE:<li[+classes+]><a href="[+url+]">[+title+]</a></li>');
        if ($data['wrap']) {
            $tpl = $this->getCFGDef('parentRowTpl',
                '@CODE:<li[+classes+]><a href="[+url+]">[+title+]</a>[+wrap+]</li>');
            if ((isset($data['template']) && !$data['template']) || (isset($data['link_attributes']) && strpos($data['link_attributes'],
                        'category') !== false)
            ) {
                $tpl = $this->getCFGDef('categoryFolderTpl', $tpl);
            } elseif ($data['here']) {
                $tpl = $this->getCFGDef('parentRowHereTpl', $tpl);
            } elseif ($data['active']) {
                $tpl = $this->getCFGDef('parentRowActiveTpl', $tpl);
            }
        } elseif ($data['level'] > 1) {
            $tpl = $this->getCFGDef('innerRowTpl', $tpl);
            if ($data['here']) {
                $tpl = $this->getCFGDef('innerRowHereTpl', $tpl);
            }
        } else {
            if ($data['here']) {
                $tpl = $this->getCFGDef('rowHereTpl', $tpl);
            }
        }

        return $tpl;
    }

    /**
     * @param array $data
     * @param mixed $fields
     * @param array $array
     * @return string
     */
    public function getJSON($data, $fields, $array = array())
    {
        $currentLevel = &$this->currentLevel;
        $currentLevel = count($this->levels);
        $docs = $this->levels;
        /** @var prepare_DL_Extender_ $extPrepare */
        $extPrepare = $this->getExtender('prepare');

        while ($currentLevel > 0) {
            foreach ($docs[$currentLevel] as $id => &$data) {
                if ($out = $this->prepareData($data)) {
                    if (is_array($out)) {
                        $data = $out;
                    }
                };

                if (isset($data['here']) || isset($data['active'])) {
                    $docs[$currentLevel - 1][$data[$this->parentField]]['active'] = 1;
                }

                if ($extPrepare) {
                    $data = $extPrepare->init($this, array(
                        'data'      => $data,
                        'nameParam' => 'prepare'
                    ));
                    if (is_bool($data) && $data === false) {
                        continue;
                    }
                }

                $hideSubMenus = $this->getCFGDef('hideSubMenus', 0);
                $hideSubMenus = !$hideSubMenus || ($hideSubMenus && in_array((int)$data[$this->parentField],
                            $this->activeBranch));
                if ($hideSubMenus) {
                    $docs[$currentLevel - 1][$data[$this->parentField]]['children'][] = $data;
                }
            }
            unset($docs[$currentLevel]);
            $currentLevel--;
        }
        unset($data);

        $out = $docs[0][0]['children'];
        unset($docs);

        return json_encode($out, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Получение информации из конфига с учетом уровня меню
     *
     * @param string $name имя параметра в конфиге
     * @param mixed $def значение по умолчанию, если в конфиге нет искомого параметра
     * @return mixed значение из конфига
     */
    public function getCFGDef($name, $def = null)
    {
        return parent::getCFGDef($name . $this->currentLevel, parent::getCFGDef($name, $def));
    }
}
