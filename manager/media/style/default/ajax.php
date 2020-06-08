<?php
define('IN_MANAGER_MODE', true);  // we use this to make sure files are accessed through
define('MODX_API_MODE', true);
if (file_exists(dirname(__DIR__, 3) . '/config.php')) {
    $config = require dirname(__DIR__) . '/config.php';
} elseif (file_exists(dirname(__DIR__, 4) . '/config.php')) {
    $config = require dirname(__DIR__, 4) . '/config.php';
} else {
    $config = [
        'root' => dirname(__DIR__, 4)
    ];
}

if (!empty($config['root']) && file_exists($config['root']. '/index.php')) {
    require_once $config['root'] . '/index.php';
} else {
    echo "<h3>Unable to load configuration settings</h3>";
    echo "Please run the Evolution CMS <a href='../install'>install utility</a>";
    exit;
}

$modx->getSettings();

if (!isset($_SESSION['mgrValidated']) || !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') || ($_SERVER['REQUEST_METHOD'] != 'POST')) {
    $modx->sendErrorPage();
}

$modx->sid = session_id();

$_lang = ManagerTheme::getLexicon();
$_style = ManagerTheme::getStyle();

$action = get_by_key($_REQUEST, 'a', '', 'is_scalar');
$frame = get_by_key($_REQUEST, 'f', '', 'is_scalar');
$role = isset($_SESSION['mgrRole']) && $_SESSION['mgrRole'] == 1 ? 1 : 0;
$docGroups = isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups']) ? implode(',', $_SESSION['mgrDocgroups']) : '';

// set limit sql query
$limit = $modx->getConfig('number_of_results');
header('Content-Type: text/html; charset='.$modx->getConfig('modx_charset'), true);

if (isset($action)) {
    switch ($action) {
        case '1': {
            switch ($frame) {
                case 'nodes':

                    // save folderstate
                    if (isset($_REQUEST['opened'])) {
                        $_SESSION['openedArray'] = $_REQUEST['opened'];
                    }
                    if (isset($_REQUEST['savestateonly'])) {
                        exit('send some data');
                    } //??

                    $indent = (int)$_REQUEST['indent'];
                    $parent = (int)$_REQUEST['parent'];
                    $expandAll = (int)$_REQUEST['expandAll'];
                    $output = '';
                    $hereid = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : '';

                    if (isset($_REQUEST['showonlyfolders'])) {
                        $_SESSION['tree_show_only_folders'] = $_REQUEST['showonlyfolders'];
                    }

                    // setup sorting
                    $sortParams = array(
                        'tree_sortby',
                        'tree_sortdir',
                        'tree_nodename'
                    );
                    foreach ($sortParams as $param) {
                        if (isset($_REQUEST[$param])) {
                            $_SESSION[$param] = $_REQUEST[$param];
                            $modx->getManagerApi()->saveLastUserSetting($param, $_REQUEST[$param]);
                        }
                    }

                    // icons by content type
                    $icons = getIconInfo($_style);

                    if (isset($_SESSION['openedArray'])) {
                        $opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
                    } else {
                        $opened = array();
                    }

                    $opened2 = array();
                    $closed2 = array();

                    //makeHTML($indent, $parent, $expandAll, $hereid);
                    echo makeHTML($indent, $parent, $expandAll, $hereid);

                    // check for deleted documents on reload
                    if ($expandAll == 2) {
                        if (!is_null(\EvolutionCMS\Models\SiteContent::query()
                            ->where('deleted', 1)->first())) {
                            echo '<span id="binFull"></span>'; // add a special element to let system now that the bin is full
                        }
                    }
                    break;
            }

            break;
        }

        case '76': {

            $elements = isset($_REQUEST['elements']) && is_scalar($_REQUEST['elements']) ? htmlentities($_REQUEST['elements']) : '';

            if ($elements) {
                $output = '';
                $items = '';
                $sql = '';
                $a = '';
                $filter = !empty($_REQUEST['filter']) && is_scalar($_REQUEST['filter']) ? addcslashes(trim($_REQUEST['filter']), '%*_') : '';

                switch ($elements) {
                    case 'element_templates':
                        $a = 16;
                        $sql = \EvolutionCMS\Models\SiteTemplate::query()
                            ->select('id', 'templatename', 'templatename as name', 'locked')
                            ->orderBy('templatename', 'ASC')
                            ->take($limit);
                        if($filter != ''){
                            $sql = $sql->where('templatename', 'LIKE', '%'.$filter.'%');
                        }

                        if ($modx->hasPermission('new_template')) {
                            $output .= '<li><a id="a_19" href="index.php?a=19" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_template'] . '</a></li>';
                        }

                        break;

                    case 'element_tplvars':
                        $a = 301;
                        $sql = \EvolutionCMS\Models\SiteTmplvar::query()->select('site_tmplvars.id', 'site_tmplvars.name', 'site_tmplvars.locked', 'site_tmplvar_templates.tmplvarid', 'site_tmplvar_templates.tmplvarid as disabled')
                            ->leftJoin('site_tmplvar_templates', function ($join) {
                                $join->on('site_tmplvar_templates.tmplvarid', '=', 'site_tmplvars.id');
                                $join->on('site_tmplvar_templates.templateid', '>', \DB::raw(0));
                            })
                            ->orderBy('site_tmplvars.name')
                            ->groupBy(['site_tmplvars.id', 'site_tmplvars.name', 'site_tmplvars.locked', 'site_tmplvar_templates.tmplvarid'])
                            ->take($limit);
                        if($filter != ''){
                            $sql = $sql->where('site_tmplvars.name', 'LIKE', '%'.$filter.'%');
                        }

                        if ($modx->hasPermission('edit_template') && $modx->hasPermission('edit_snippet') && $modx->hasPermission('edit_chunk') && $modx->hasPermission('edit_plugin')) {
                            $output .= '<li><a id="a_300" href="index.php?a=300" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_tmplvars'] . '</a></li>';
                        }

                        break;

                    case 'element_htmlsnippets':
                        $a = 78;
                        $sql = \EvolutionCMS\Models\SiteHtmlsnippet::select('id', 'name', 'locked', 'disabled')
                            ->orderBy('name', 'ASC')->take($limit);
                        if($filter != ''){
                            $sql = $sql->where('name', 'LIKE', '%'.$filter.'%');
                        }

                        if ($modx->hasPermission('new_chunk')) {
                            $output .= '<li><a id="a_77" href="index.php?a=77" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_htmlsnippet'] . '</a></li>';
                        }

                        break;

                    case 'element_snippets':
                        $a = 22;
                        $sql = \EvolutionCMS\Models\SiteSnippet::select('id', 'name', 'locked', 'disabled')
                            ->orderBy('name', 'ASC')->take($limit);
                        if($filter != ''){
                            $sql = $sql->where('name', 'LIKE', '%'.$filter.'%');
                        }

                        if ($modx->hasPermission('new_snippet')) {
                            $output .= '<li><a id="a_23" href="index.php?a=23" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_snippet'] . '</a></li>';
                        }

                        break;

                    case 'element_plugins':
                        $a = 102;
                        $sql = \EvolutionCMS\Models\SitePlugin::select('id', 'name', 'locked', 'disabled')
                            ->orderBy('name', 'ASC')->take($limit);
                        if($filter != ''){
                            $sql = $sql->where('name', 'LIKE', '%'.$filter.'%');
                        }

                        if ($modx->hasPermission('new_plugin')) {
                            $output .= '<li><a id="a_101" href="index.php?a=101" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_plugin'] . '</a></li>';
                        }

                        break;
                }

                if ($sql->count()>0) {
                    if ($sql->count() == $limit) {
                        $output .= '<li class="item-input"><input type="text" name="filter" class="dropdown-item form-control form-control-sm" autocomplete="off" /></li>';
                    }
                    foreach ($sql->get() as $row){
                        $row = $row->toArray();
                        if($a == 301 && !isset($row['disabled'])) $row['disabled'] = 1;
                        if(!isset($row['disabled'])) $row['disabled'] = 0;
                        if (($row['disabled'] || $row['locked']) && $role != 1) {
                            continue;
                        }

                        $items .= '<li class="item ' . ($row['disabled'] ? 'disabled' : '') . ($row['locked'] ? ' locked' : '') . '"><a id="a_' . $a . '__id_' . $row['id'] . '" href="index.php?a=' . $a . '&id=' . $row['id'] . '" target="main" data-parent-id="a_76__elements_' . $elements . '">' . entities($row['name'], $modx->getConfig('modx_charset')) . ' <small>(' . $row['id'] . ')</small></a></li>' . "\n";
                    }
                }

                if (isset($_REQUEST['filter'])) {
                    $output = $items;
                } else {
                    $output .= $items;
                }

                echo $output;
            }

            break;
        }

        case '75': {
            $a = 12;
            $output = '';
            $items = '';
            $filter = !empty($_REQUEST['filter']) && is_scalar($_REQUEST['filter']) ? addcslashes(trim($_REQUEST['filter']), '\%*_') : '';

            $sql = \EvolutionCMS\Models\ManagerUser::select('manager_users.*', 'user_attributes.blocked')
                ->leftJoin('user_attributes', 'manager_users.id','=','user_attributes.internalKey')
                ->orderBy('manager_users.username')->take($limit);
            if($filter != ''){
                $sql = $sql->where('manager_users.username', 'LIKE', '%'.$filter.'%');
            }
            if(!$modx->hasPermission('save_role')) {
                $sql = $sql->where('user_attributes.role', '!=', \DB::raw(1));
            }


            if ($modx->hasPermission('new_user')) {
                $output .= '<li><a id="a_11" href="index.php?a=11" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_user'] . '</a></li>';
            }

            if ($count = $sql->count()) {
                if ($count == $limit) {
                    $output .= '<li class="item-input"><input type="text" name="filter" class="dropdown-item form-control form-control-sm" autocomplete="off" /></li>';
                }
                foreach ($sql->get() as $row){
                    $items .= '<li class="item ' . ($row->blocked ? 'disabled' : '') . '"><a id="a_' . $a . '__id_' . $row->id . '" href="index.php?a=' . $a . '&id=' . $row->id . '" target="main">' . entities($row->username, $modx->getConfig('modx_charset')) . ' <small>(' . $row->id . ')</small></a></li>';
                }
            }

            if (isset($_REQUEST['filter'])) {
                $output = $items;
            } else {
                $output .= $items;
            }

            echo $output;

            break;
        }

        case '99': {
            $a = 88;
            $output = '';
            $items = '';
            $filter = !empty($_REQUEST['filter']) && is_scalar($_REQUEST['filter']) ? addcslashes(trim($_REQUEST['filter']), '\%*_') : '';

            $sql = \EvolutionCMS\Models\WebUser::select('web_users.*', 'web_user_attributes.blocked')
                ->leftJoin('web_user_attributes', 'web_users.id','=','web_user_attributes.internalKey')
                ->orderBy('web_users.username')->take($limit);
            if($filter != ''){
                $sql = $sql->where('web_users.username', 'LIKE', '%'.$filter.'%');
            }

            if ($modx->hasPermission('new_web_user')) {
                $output .= '<li><a id="a_87" href="index.php?a=87" target="main"><i class="' . $_style['icon_add'] . '"></i>' . $_lang['new_web_user'] . '</a></li>';
            }

            if ($count = $sql->count()) {
                if ($count == $limit) {
                    $output .= '<li class="item-input"><input type="text" name="filter" class="dropdown-item form-control form-control-sm" autocomplete="off" /></li>';
                }
                foreach ($sql->get() as $row){
                    $items .= '<li class="item ' . ($row->blocked ? 'disabled' : '') . '"><a id="a_' . $a . '__id_' . $row->id . '" href="index.php?a=' . $a . '&id=' . $row->id . '" target="main">' . entities($row->username, $modx->getConfig('modx_charset')) . ' <small>(' . $row->id . ')</small></a></li>';
                }
            }

            if (isset($_REQUEST['filter'])) {
                $output = $items;
            } else {
                $output .= $items;
            }

            echo $output;

            break;
        }

        case 'modxTagHelper': {
            $name = isset($_REQUEST['name']) && is_scalar($_REQUEST['name']) ? $modx->getDatabase()->escape($_REQUEST['name']) : false;
            $type = isset($_REQUEST['type']) && is_scalar($_REQUEST['type']) ? $modx->getDatabase()->escape($_REQUEST['type']) : false;
            $contextmenu = '';

            if ($role && $name && $type) {
                switch ($type) {
                    case 'Snippet':
                    case 'SnippetNoCache': {

                        $sql = $modx->getDatabase()->query('SELECT *
						FROM ' . $modx->getFullTableName('site_snippets') . '
						WHERE name="' . $name . '"
						LIMIT 1');

                        if ($modx->getDatabase()->getRecordCount($sql)) {
                            $row = $modx->getDatabase()->getRow($sql);
                            $contextmenu = array(
                                'header' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_code'] . '"></i> ' . entities($row['name'], $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_edit'] . '"></i> ' . $_lang['edit'],
                                    'url' => "index.php?a=22&id=" . $row['id']
                                )
                            );
                            if (!empty($row['description'])) {
                                $contextmenu['seperator'] = '';
                                $contextmenu['description'] = array(
                                    'innerHTML' => '<i class="' . $_style['icon_info'] . '"></i> ' . entities($row['description'], $modx->getConfig('modx_charset'))
                                );
                            }
                        } else {
                            $contextmenu = array(
                                'header' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_code'] . '"></i> ' . entities($name, $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_add'] . '"></i> ' . $_lang['new_snippet'],
                                    'url' => "index.php?a=23&itemname=" . entities($name, $modx->getConfig('modx_charset'))
                                )
                            );
                        }

                        break;
                    }
                    case 'Chunk' : {

                        $sql = $modx->getDatabase()->query('SELECT *
						FROM ' . $modx->getFullTableName('site_htmlsnippets') . '
						WHERE name="' . $name . '"
						LIMIT 1');

                        if ($modx->getDatabase()->getRecordCount($sql)) {
                            $row = $modx->getDatabase()->getRow($sql);
                            $contextmenu = array(
                                'header' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_chunk'] . '"></i> ' . entities($row['name'], $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_edit'] . '"></i> ' . $_lang['edit'],
                                    'url' => "index.php?a=78&id=" . $row['id']
                                )
                            );
                            if (!empty($row['description'])) {
                                $contextmenu['seperator'] = '';
                                $contextmenu['description'] = array(
                                    'innerHTML' => '<i class="' . $_style['icon_info'] . '"></i> ' . entities($row['description'], $modx->getConfig('modx_charset'))
                                );
                            }
                        } else {
                            $contextmenu = array(
                                'header' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_chunk'] . '"></i> ' . entities($name, $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_add'] . '"></i> ' . $_lang['new_htmlsnippet'],
                                    'url' => "index.php?a=77&itemname=" . entities($name, $modx->getConfig('modx_charset'))
                                )
                            );
                        }

                        break;
                    }
                    case 'AttributeValue': {
                        $sql = $modx->getDatabase()->query('SELECT *
						FROM ' . $modx->getFullTableName('site_htmlsnippets') . '
						WHERE name="' . $name . '"
						LIMIT 1');

                        if ($modx->getDatabase()->getRecordCount($sql)) {
                            $row = $modx->getDatabase()->getRow($sql);
                            $contextmenu = array(
                                'header' => array(
                                    'innerText' => entities($row['name'], $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_edit'] . '"></i> ' . $_lang['edit'],
                                    'url' => "index.php?a=78&id=" . $row['id']
                                )
                            );
                            if (!empty($row['description'])) {
                                $contextmenu['seperator'] = '';
                                $contextmenu['description'] = array(
                                    'innerHTML' => '<i class="' . $_style['icon_info'] . '"></i> ' . entities($row['description'], $modx->getConfig('modx_charset'))
                                );
                            }
                        } else {

                            $sql = $modx->getDatabase()->query('SELECT *
							FROM ' . $modx->getFullTableName('site_snippets') . '
							WHERE name="' . $name . '"
							LIMIT 1');

                            if ($modx->getDatabase()->getRecordCount($sql)) {
                                $row = $modx->getDatabase()->getRow($sql);
                                $contextmenu = array(
                                    'header' => array(
                                        'innerHTML' => '<i class="' . $_style['icon_code'] . '"></i> ' . entities($row['name'], $modx->getConfig('modx_charset'))
                                    ),
                                    'item' => array(
                                        'innerHTML' => '<i class="' . $_style['icon_edit'] . '"></i> ' . $_lang['edit'],
                                        'url' => "index.php?a=22&id=" . $row['id']
                                    )
                                );
                                if (!empty($row['description'])) {
                                    $contextmenu['seperator'] = '';
                                    $contextmenu['description'] = array(
                                        'innerHTML' => '<i class="' . $_style['icon_info'] . '"></i> ' . entities($row['description'], $modx->getConfig('modx_charset'))
                                    );
                                }
                            } else {
                                $contextmenu = array(
                                    'header' => array(
                                        'innerHTML' => '<i class="' . $_style['icon_code'] . '"></i> ' . entities($name, $modx->getConfig('modx_charset'))
                                    ),
                                    'item' => array(
                                        'innerHTML' => '<i class="' . $_style['icon_add'] . '"></i> ' . $_lang['new_htmlsnippet'],
                                        'url' => "index.php?a=77&itemname=" . entities($name, $modx->getConfig('modx_charset'))
                                    ),
                                    'item2' => array(
                                        'innerHTML' => '<i class="' . $_style['icon_add'] . '"></i> ' . $_lang['new_snippet'],
                                        'url' => "index.php?a=23&itemname=" . entities($name, $modx->getConfig('modx_charset'))
                                    )
                                );
                            }
                        }

                        break;
                    }
                    case 'Placeholder' :
                    case 'Tv' : {
                        $default_field = array(
                            'id',
                            'type',
                            'contentType',
                            'pagetitle',
                            'longtitle',
                            'description',
                            'alias',
                            'link_attributes',
                            'published',
                            'pub_date',
                            'unpub_date',
                            'parent',
                            'isfolder',
                            'introtext',
                            'content',
                            'richtext',
                            'template',
                            'menuindex',
                            'searchable',
                            'cacheable',
                            'createdon',
                            'createdby',
                            'editedon',
                            'editedby',
                            'deleted',
                            'deletedon',
                            'deletedby',
                            'publishedon',
                            'publishedby',
                            'menutitle',
                            'donthit',
                            'haskeywords',
                            'hasmetatags',
                            'privateweb',
                            'privatemgr',
                            'content_dispo',
                            'hidemenu',
                            'alias_visible'
                        );

                        if (in_array($name, $default_field)) {
                            return;
                        }

                        $sql = $modx->getDatabase()->query('SELECT *
						FROM ' . $modx->getFullTableName('site_tmplvars') . '
						WHERE name="' . $name . '"
						LIMIT 1');

                        if ($modx->getDatabase()->getRecordCount($sql)) {
                            $row = $modx->getDatabase()->getRow($sql);
                            $contextmenu = array(
                                'header' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_tv'] . '"></i> ' . entities($row['name'], $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_edit'] . '"></i> ' . $_lang['edit'],
                                    'url' => "index.php?a=301&id=" . $row['id']
                                )
                            );
                            if (!empty($row['description'])) {
                                $contextmenu['seperator'] = '';
                                $contextmenu['description'] = array(
                                    'innerHTML' => '<i class="' . $_style['icon_info'] . '"></i> ' . entities($row['description'], $modx->getConfig('modx_charset'))
                                );
                            }
                        } else {
                            $contextmenu = array(
                                'header' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_tv'] . '"></i> ' . entities($name, $modx->getConfig('modx_charset'))
                                ),
                                'item' => array(
                                    'innerHTML' => '<i class="' . $_style['icon_add'] . '"></i> ' . $_lang['new_tmplvars'],
                                    'url' => "index.php?a=300&itemname=" . entities($name, $modx->getConfig('modx_charset'))
                                )
                            );
                        }

                        break;
                    }
                }
                echo json_encode($contextmenu, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
                break;
            }

            break;
        }

        case 'movedocument' : {
            $json = array();

            if ($modx->hasPermission('new_document') && $modx->hasPermission('edit_document') && $modx->hasPermission('save_document')) {
                $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : '';
                $parent = isset($_REQUEST['parent']) ? (int)$_REQUEST['parent'] : 0;
                $menuindex = isset($_REQUEST['menuindex']) && is_scalar($_REQUEST['menuindex']) ? $_REQUEST['menuindex'] : 0;

                // set parent
                if ($id && $parent >= 0) {

                    // find older parent
                    $parentOld = $modx->getDatabase()->getValue($modx->getDatabase()->select('parent', $modx->getFullTableName('site_content'), 'id=' . $id));

                    $eventOut = $modx->invokeEvent('onBeforeMoveDocument', [
                        'id_document' => $id,
                        'old_parent'  => $parentOld,
                        'new_parent'  => $parent,
                    ]);

                    if (is_array($eventOut) && count($eventOut) > 0) {
                        $eventParent = array_pop($eventOut);

                        if ($eventParent == $parentOld) {
                            $json['errors'] = $_lang['error_movedocument2'];
                        } else {
                            $parent = $eventParent;
                        }
                    }

                    if (empty($json['errors'])) {
                        // check privileges user for move docs
                        if (!empty($modx->config['tree_show_protected']) && $role != 1) {
                            $sql = $modx->getDatabase()->select('*', $modx->getFullTableName('document_groups'), 'document IN(' . $id . ',' . $parent . ',' . $parentOld . ')');
                            if ($modx->getDatabase()->getRecordCount($sql)) {
                                $document_groups = array();
                                while ($row = $modx->getDatabase()->getRow($sql)) {
                                    $document_groups[$row['document']]['groups'][] = $row['document_group'];
                                }
                                foreach ($document_groups as $key => $value) {
                                    if (($key == $parent || $key == $parentOld || $key == $id) && !in_array($role, $value['groups'])) {
                                        $json['errors'] = $_lang["error_no_privileges"];
                                    }
                                }
                                if ($json['errors']) {
                                    header('content-type: application/json');
                                    echo json_encode($json, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
                                    break;
                                }
                            }
                        }

                        if ($parent == 0 && $parent != $parentOld && !$modx->config['udperms_allowroot'] && $role != 1) {
                            $json['errors'] = $_lang["error_no_privileges"];
                        } else {
                            // set new parent
                            $modx->getDatabase()->update(array(
                                'parent' => $parent
                            ), $modx->getFullTableName('site_content'), 'id=' . $id);
                            // set parent isfolder = 1
                            $modx->getDatabase()->update(array(
                                'isfolder' => 1
                            ), $modx->getFullTableName('site_content'), 'id=' . $parent);

                            if ($parent != $parentOld) {
                                // check children docs and set parent isfolder
                                if ($modx->getDatabase()->getRecordCount($modx->getDatabase()->select('id', $modx->getFullTableName('site_content'), 'parent=' . $parentOld))) {
                                    $modx->getDatabase()->update(array(
                                        'isfolder' => 1
                                    ), $modx->getFullTableName('site_content'), 'id=' . $parentOld);
                                } else {
                                    $modx->getDatabase()->update(array(
                                        'isfolder' => 0
                                    ), $modx->getFullTableName('site_content'), 'id=' . $parentOld);
                                }
                            }

                            // set menuindex
                            if (!empty($menuindex)) {
                                $menuindex = explode(',', $menuindex);
                                foreach ($menuindex as $key => $value) {
                                    $modx->getDatabase()->query('UPDATE ' . $modx->getFullTableName('site_content') . ' SET menuindex=' . $key . ' WHERE id=' . $value);
                                }
                            } else {
                                // TODO: max(*) menuindex
                            }

                            if (!$json['errors']) {
                                $json['success'] = $_lang["actioncomplete"];

                                $modx->invokeEvent('onAfterMoveDocument', [
                                    'id_document' => $id,
                                    'old_parent'  => $parentOld,
                                    'new_parent'  => $parent,
                                ]);
                            }
                        }
                    }
                }
            } else {
                $json['errors'] = $_lang["error_no_privileges"];
            }

            header('content-type: application/json');
            echo json_encode($json, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

            break;
        }

        case 'getLockedElements': {
            $type = isset($_REQUEST['type']) ? (int)$_REQUEST['type'] : 0;
            $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

            $output = !!$modx->elementIsLocked($type, $id, true);

            if (!$output) {
                $docgrp = (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) ? implode(',', $_SESSION['mgrDocgroups']) : '';
                $docgrp_cond = $docgrp ? ' OR dg.document_group IN (' . $docgrp . ')' : '';
                $sql = '
                    SELECT MAX(IF(1=' . $role . ' OR sc.privatemgr=0' . $docgrp_cond . ', 0, 1)) AS locked
                    FROM ' . $modx->getFullTableName('site_content') . ' AS sc 
                    LEFT JOIN ' . $modx->getFullTableName('document_groups') . ' dg ON dg.document=sc.id
                    WHERE sc.id=' . $id . ' GROUP BY sc.id';
                $sql = $modx->getDatabase()->query($sql);
                if ($modx->getDatabase()->getRecordCount($sql)) {
                    $row = $modx->getDatabase()->getRow($sql);
                    $output = !!$row['locked'];
                }
            }

            echo $output;

            break;
        }
    }
}
