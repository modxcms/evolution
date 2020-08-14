<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    exit();
}
unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes
// Catch $_REQUEST['searchid'] for compatibility
if (isset($_REQUEST['searchid'])) {
    $_REQUEST['searchfields'] = $_REQUEST['searchid'];
    $_POST['searchfields'] = $_REQUEST['searchid'];
}
?>

    <script language="javascript">
        var actions = {
            cancel: function () {
                documentDirty = false;
                document.location.href = 'index.php?a=2';
            }
        };
    </script>

    <h1>
        <i class="<?= $_style['icon_search'] ?>"></i><?= $_lang['search_criteria'] ?>
    </h1>

<?= ManagerTheme::getStyle('actionbuttons.static.cancel') ?>

    <div class="tab-page">
        <div class="container container-body">
            <form name="searchform" method="post" action="index.php" enctype="multipart/form-data" class="form-group">
                <input type="hidden" name="a" value="71">
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2"><?= $_lang['search_criteria_top'] ?></div>
                    <div class="col-md-9 col-lg-10">
                        <input name="searchfields" type="text"
                               value="<?= entities(get_by_key($_REQUEST, 'searchfields', '', 'is_scalar'), $modx->getConfig('modx_charset')) ?>"/>
                        <small class="form-text"><?= $_lang['search_criteria_top_msg'] ?></small>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2"><?= $_lang['search_criteria_template_id'] ?></div>
                    <div class="col-md-9 col-lg-10">
                        <?php
                        $option[] = '<option value="">' . $_lang['none'] . '</option>';
                        $templateid = (isset($_REQUEST['templateid']) && $_REQUEST['templateid'] !== '') ? (int)$_REQUEST['templateid'] : '';
                        $selected = $templateid === 0 ? ' selected="selected"' : '';
                        $option[] = '<option value="0"' . $selected . '>(blank)</option>';
                        $templates = \EvolutionCMS\Models\SiteTemplate::all()->toArray();
                        foreach($templates as $row) {
                            $templatename = htmlspecialchars($row['templatename'], ENT_QUOTES, $modx->getConfig('modx_charset'));
                            $selected = $row['id'] == $templateid ? ' selected="selected"' : '';
                            $option[] = sprintf('<option value="%s"%s>%s(%s)</option>', $row['id'], $selected, $templatename, $row['id']);
                        }
                        $tpls = sprintf('<select name="templateid">%s</select>', implode("\n", $option));
                        ?>
                        <?= $tpls ?>
                        <small class="form-text"><?= $_lang['search_criteria_template_id_msg'] ?></small>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2">URL</div>
                    <div class="col-md-9 col-lg-10">
                        <input name="url" type="text"
                               value="<?= entities(get_by_key($_REQUEST, 'url', '', 'is_scalar'), $modx->getConfig('modx_charset')) ?>"/>
                        <small class="form-text"><?= $_lang['search_criteria_url_msg'] ?></small>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-3 col-lg-2"><?= $_lang['search_criteria_content'] ?></div>
                    <div class="col-md-9 col-lg-10">
                        <input name="content" type="text"
                               value="<?= entities(get_by_key($_REQUEST, 'content', '', 'is_scalar'), $modx->getConfig('modx_charset')) ?>"/>
                        <small class="form-text"><?= $_lang['search_criteria_content_msg'] ?></small>
                    </div>
                </div>

                <a class="btn btn-success" href="javascript:;" onClick="document.searchform.submitok.click();"><i
                            class="<?= $_style["icon_search"] ?>"></i> <?= $_lang['search'] ?>
                </a>
                <a class="btn btn-secondary" href="index.php?a=2"><i
                            class="<?= $_style["icon_cancel"] ?>"></i> <?= $_lang['cancel'] ?></a>
                <input type="submit" value="Search" name="submitok" style="display:none"/>
            </form>
        </div>
    </div>
<?php
//TODO: сделать поиск по уму пока сделаю что б одно поле было для id,longtitle,pagetitle,alias далее нужно думаю добавить что б и в елементах искало
if (isset($_REQUEST['submitok'])) {
    $searchQuery = \EvolutionCMS\Models\SiteContent::query()->select(
        'site_content.id', 'pagetitle', 'longtitle', 'description', 'introtext', 'menutitle', 'deleted', 'published', 'isfolder', 'type'
    );

    $searchfields = trim(get_by_key($_REQUEST, 'searchfields', '', 'is_scalar'));

    $templateid = isset($_REQUEST['templateid']) && $_REQUEST['templateid'] !== '' ? (int)$_REQUEST['templateid'] : '';
    $searchcontent = get_by_key($_REQUEST, 'content', '', 'is_scalar');


    // Handle Input "Search by exact URL"
    $idFromAlias = false;
    if (isset($_REQUEST['url']) && $_REQUEST['url'] !== '') {
        $url = $_REQUEST['url'];
        $friendly_url_suffix = $modx->getConfig('friendly_url_suffix');
        $base_url = MODX_BASE_URL;
        $site_url = MODX_SITE_URL;
        $url = preg_replace('@' . $friendly_url_suffix . '$@', '', $url);
        if ($url[0] === '/') {
            $url = preg_replace('@^' . $base_url . '@', '', $url);
        }
        if (substr($url, 0, 4) === 'http') {
            $url = preg_replace('@^' . $site_url . '@', '', $url);
        }
        $idFromAlias = $modx->getIdFromAlias($url);
    }

    // Handle Input "Search in main fields"
    if ($searchfields != '') {

        /*start search by TV. Added Rising13*/

        $tvs = \EvolutionCMS\Models\SiteTmplvarContentvalue::query()->where('value', 'LIKE', '%' . $searchfields . '%');

        if ($tvs->count() > 0) {
            $articul_id = [];
            $i = 1;
            foreach ($tvs->pluck('contentid')->toArray() as $articul) {
                $articul_id[] = $articul;
            }
            $searchQuery = $searchQuery->orWhereIn('site_content.id', $articul_id);
        }
        /*end search by TV*/

        if (ctype_digit($searchfields)) {
            $searchQuery->orWhere('site_content.id', $searchfields);
            if (strlen($searchfields) > 3) {
                $searchQuery->orWhere('site_content.pagetitle', 'LIKE', '%' . $searchfields . '%');
            }
        }
        if ($idFromAlias) {
            $searchQuery->orWhere('site_content.id', $idFromAlias);

        }


        if (!ctype_digit($searchfields)) {
            $searchQuery = $searchQuery->where(function ($query) use ($searchfields) {
                $query->where('pagetitle', 'LIKE', '%' . $searchfields . '%')
                    ->orWhere('longtitle', 'LIKE', '%' . $searchfields . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchfields . '%')
                    ->orWhere('introtext', 'LIKE', '%' . $searchfields . '%')
                    ->orWhere('menutitle', 'LIKE', '%' . $searchfields . '%')
                    ->orWhere('alias', 'LIKE', '%' . $searchfields . '%');
            });

        }
    } elseif ($idFromAlias) {
        $searchQuery = $searchQuery->where('site_content.id', $idFromAlias);
    }

    // Handle Input "Search by template ID"
    if ($templateid !== '') {
        $searchQuery = $searchQuery->where('site_content.template', $templateid);

    }

    // Handle Input "Search by content"
    if ($searchcontent !== '') {
        $searchQuery = $searchQuery->where('site_content.content', $searchcontent);
    }

    // get document groups for current user
    if (!empty($modx->config['use_udperms'])) {
        $docgrp = (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) ? implode(',', $_SESSION['mgrDocgroups']) : '';
        $mgrRole = (isset ($_SESSION['mgrRole']) && $_SESSION['mgrRole'] == 1) ? 1 : 0;
        if ($mgrRole != 1) {
            if (isset($_SESSION['mgrDocgroups']) && is_array($_SESSION['mgrDocgroups'])) {
                $searchQuery = $searchQuery->join('document_groups', 'site_content.id', '=', 'document_groups.document')
                    ->where(function ($query) use ($searchfields) {
                    $query->where('privatemgr', 0)
                        ->orWhereIn('document_group', $_SESSION['mgrDocgroups']);
                });
            } else {
                $searchQuery = $searchQuery->where('privatemgr', 0);
            }
        }
    }

    $searchQuery = $searchQuery->groupBy('site_content.id');

    $limit = $searchQuery->count();
    ?>
    <div class="container navbar">
        <?= $_lang['search_results'] ?>
    </div>

    <div class="tab-page">
        <div class="container container-body">
            <?php
            if ((int)get_by_key($_GET, 'ajax', 0) !== 1) {
                if ($limit < 1) {
                    echo $_lang['search_empty'];
                } else {
                    printf('<p>' . $_lang['search_results_returned_msg'] . '</p>', $limit);
                    ?>
                    <script type="text/javascript" src="media/script/tablesort.js"></script>
                    <table class="grid sortabletable sortable-onload-2 rowstyle-even" id="table-1">
                        <thead>
                        <tr>
                            <th width="40"></th>
                            <th width="40" class="sortable"><b><?= $_lang['search_results_returned_id'] ?></b></th>
                            <th width="40"></th>
                            <th class="sortable"><b><?= $_lang['search_results_returned_title'] ?></b></th>
                            <th class="sortable"><b><?= $_lang['search_results_returned_desc'] ?></b></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        // icons by content type
                        $icons = array(
                            'text/plain' => '<i class="' . $_style['icon_document'] . '"></i>',
                            'text/html' => '<i class="' . $_style['icon_document'] . '"></i>',
                            'text/xml' => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'text/css' => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'text/javascript' => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'image/gif' => '<i class="' . $_style['icon_image'] . '"></i>',
                            'image/jpg' => '<i class="' . $_style['icon_image'] . '"></i>',
                            'image/png' => '<i class="' . $_style['icon_image'] . '"></i>',
                            'application/pdf' => '<i class="' . $_style['icon_pdf'] . '"></i>',
                            'application/rss+xml' => '<i class="' . $_style['icon_code_file'] . '"></i>',
                            'application/vnd.ms-word' => '<i class="' . $_style['icon_word'] . '"></i>',
                            'application/vnd.ms-excel' => '<i class="' . $_style['icon_excel'] . '"></i>',
                        );
                        foreach ($searchQuery->get()->toArray() as $row) {
                            // figure out the icon for the document...
                            $icon = "";
                            if ($row['type'] == 'reference') {
                                $icon .= $_style["tree_linkgo"];
                            } elseif ($row['isfolder'] == 0) {
                                $icon .= isset($icons[$row['contenttype']]) ? $icons[$row['contenttype']] : '<i class="' . $_style['icon_document'] . '"></i>';
                            } else {
                                $icon .= '<i class="' . $_style['icon_folder'] . '"></i>';
                            }

                            $tdClass = "";
                            if ($row['published'] == 0) {
                                $tdClass .= ' class="unpublishedNode"';
                            }
                            if ($row['deleted'] == 1) {
                                $tdClass .= ' class="deletedNode"';
                            }
                            ?>
                            <tr>
                                <td class="text-center">
                                    <a href="index.php?a=3&id=<?= $row['id'] ?>"
                                       title="<?= $_lang['search_view_docdata'] ?>"><i
                                                class="<?= $_style['icon_info'] ?>"/></i></a>
                                </td>
                                <td class="text-right"><?= $row['id'] ?></td>
                                <td class="text-center"><?= $icon ?></td>
                                <?php
                                if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                                    ?>
                                    <td<?= $tdClass ?>>
                                        <a href="index.php?a=27&id=<?= $row['id'] ?>"><?= mb_strlen($row['pagetitle'], ManagerTheme::getCharset()) > 70 ? mb_substr($row['pagetitle'], 0, 70, ManagerTheme::getCharset()) . "..." : $row['pagetitle'] ?></a>
                                    </td>
                                    <td<?= $tdClass ?>><?= mb_strlen($row['description'], ManagerTheme::getCharset()) > 70 ? mb_substr($row['description'], 0, 70, ManagerTheme::getCharset()) . "..." : $row['description'] ?></td>
                                    <?php
                                } else {
                                    ?>
                                    <td<?= $tdClass ?>><?= strlen($row['pagetitle']) > 20 ? substr($row['pagetitle'], 0, 20) . '...' : $row['pagetitle'] ?></td>
                                    <td<?= $tdClass ?>><?= strlen($row['description']) > 35 ? substr($row['description'], 0, 35) . '...' : $row['description'] ?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php

                }
            } else {
                $output = '';

                //docs
                if ($modx->hasPermission('new_document') && $modx->hasPermission('edit_document') && $modx->hasPermission('save_document')) {
                    $docscounts = $searchQuery->count();
                    if ($docscounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_sitemap'] . '"></i> ' . $_lang["manage_documents"] . ' (' . $docscounts . ')</b></li>';
                        foreach ($searchQuery->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList('', !$row['published'], $row['deleted']) . '>
                                <a href="index.php?a=27&id=' . $row['id'] . '" id="content_' . $row['id'] . '" target="main">' .
                                highlightingCoincidence($row['pagetitle'], $_REQUEST['searchfields']) . ' <small>(' . highlightingCoincidence($row['id'], $_REQUEST['searchfields']) . ')</small>' . '<i class="' . $_style['icon_external_link'] . '"></i>
                                </a>
                            </li>';
                        }
                    }
                }

                //templates
                if ($modx->hasPermission('edit_template')) {
                    $templates = \EvolutionCMS\Models\SiteTemplate::query()->select('id', 'templatename', 'locked')
                        ->where('id', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('templatename', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('content', 'LIKE', '%'.$searchfields.'%');
                    if ($templates->count() > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_template'] . '"></i> ' . $_lang["manage_templates"] . ' (' . $templates->count() . ')</b></li>';
                        foreach ($templates->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList($row['locked']) . '><a href="index.php?a=16&id=' . $row['id'] . '" id="templates_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['templatename'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //tvs
                if ($modx->hasPermission('edit_template') && $modx->hasPermission('edit_snippet') && $modx->hasPermission('edit_chunk') && $modx->hasPermission('edit_plugin')) {
                    $templateVars = \EvolutionCMS\Models\SiteTmplvar::query()->select('id', 'name', 'locked')
                        ->where('id', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('name', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('type', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('elements', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('display', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('display_params', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('default_text', 'LIKE', '%'.$searchfields.'%');
                    if ($templateVars->count() > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_tv'] . '"></i> ' . $_lang["settings_templvars"] . ' (' . $templateVars->count() . ')</b></li>';
                        foreach ($templateVars->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList($row['locked']) . '><a href="index.php?a=301&id=' . $row['id'] . '" id="tmplvars_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //Chunks
                if ($modx->hasPermission('edit_chunk')) {
                    $chunks = \EvolutionCMS\Models\SiteHtmlsnippet::query()->select('id', 'name', 'locked', 'disabled')
                        ->where('id', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('name', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('description', 'LIKE', '%'.$searchfields.'%')
                        ->orWhere('snippet', 'LIKE', '%'.$searchfields.'%');

                    if ($chunks->count() > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_chunk'] . '"></i> ' . $_lang["manage_htmlsnippets"] . ' (' . $chunks->count() . ')</b></li>';
                        foreach ($chunks->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=78&id=' . $row['id'] . '" id="htmlsnippets_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //Snippets
                if ($modx->hasPermission('edit_snippet')) {
                    $snippets = \EvolutionCMS\Models\SiteSnippet::query()->select('id', 'name', 'locked', 'disabled')
                        ->where('id', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('name', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('snippet', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('properties', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('moduleguid', 'LIKE', '%' . $searchfields . '%');

                    if ($snippets->count() > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_code'] . '"></i> ' . $_lang["manage_snippets"] . ' (' . $snippets->count() . ')</b></li>';
                        foreach ($snippets->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=22&id=' . $row['id'] . '" id="snippets_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //plugins
                if ($modx->hasPermission('edit_plugin')) {
                    $plugins = \EvolutionCMS\Models\SitePlugin::query()->select('id', 'name', 'locked', 'disabled')
                        ->where('id', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('name', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('plugincode', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('properties', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('moduleguid', 'LIKE', '%' . $searchfields . '%');

                    if ($plugins->count() > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_plugin'] . '"></i> ' . $_lang["manage_plugins"] . ' (' . $plugins->count() . ')</b></li>';
                        foreach ($plugins->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=102&id=' . $row['id'] . '" id="plugins_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                //modules
                if ($modx->hasPermission('edit_module')) {
                    $modules = \EvolutionCMS\Models\SiteModule::query()->select('id', 'name', 'locked', 'disabled')
                        ->where('id', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('name', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('modulecode', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('properties', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('guid', 'LIKE', '%' . $searchfields . '%')
                        ->orWhere('resourcefile', 'LIKE', '%' . $searchfields . '%');


                    $modulescounts = $modules->count();
                    if ($modulescounts > 0) {
                        $output .= '<li><b><i class="' . $_style['icon_cogs'] . '"></i> ' . $_lang["modules"] . ' (' . $modulescounts . ')</b></li>';
                        foreach ($modules->get()->toArray() as $row) {
                            $output .= '<li' . addClassForItemList($row['locked'], $row['disabled']) . '><a href="index.php?a=108&id=' . $row['id'] . '" id="modules_' . $row['id'] . '" target="main">' . highlightingCoincidence($row['name'], $_REQUEST['searchfields']) . '<i class="' . $_style['icon_external_link'] . '"></i></a></li>';
                        }
                    }
                }

                echo $output ? '<div class="ajaxSearchResults"><ul>' . $output . '</ul></div>' : '1';
            }

            ?>
        </div>
    </div>
    <?php
}
