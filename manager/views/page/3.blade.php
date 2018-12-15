<?php /** get the page to show document's data */ ?>
@extends('manager::template.page')
@section('content')
    <?php /*include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/document_data.static.php"); */?>
    <?php
    if(isset($_REQUEST['id'])) {
        $id = (int) $_REQUEST['id'];
    } else {
        $id = 0;
    }

    if(isset($_GET['opened'])) {
        $_SESSION['openedArray'] = $_GET['opened'];
    }

    // Get table names (alphabetical)
    $tbl_document_groups = $modx->getDatabase()->getFullTableName('document_groups');
    $tbl_manager_users = $modx->getDatabase()->getFullTableName('manager_users');
    $tbl_site_content = $modx->getDatabase()->getFullTableName('site_content');
    $tbl_site_templates = $modx->getDatabase()->getFullTableName('site_templates');

    // Get access permissions
    $docgrp = $_SESSION['mgrDocgroups'] ? implode(",", $_SESSION['mgrDocgroups']) : '';
    $access = "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0" . (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");

    //
    if($_SESSION['tree_show_only_folders']) {
        $parent = $id ? ($modx->getDatabase()->getValue("SELECT parent FROM " . $tbl_site_content . " WHERE id=$id LIMIT 1")) : 0;
        $isfolder = $modx->getDatabase()->getValue("SELECT isfolder FROM " . $tbl_site_content . " WHERE id=$id LIMIT 1");
        if(!$isfolder && $parent != 0) {
            $id = $_REQUEST['id'] = $parent;
        }
    }

    // Get the document content
    $rs = $modx->getDatabase()->select('DISTINCT sc.*', "{$tbl_site_content} AS sc
		LEFT JOIN {$tbl_document_groups} AS dg ON dg.document = sc.id", "sc.id ='{$id}' AND ({$access})");
    $content = $modx->getDatabase()->getRow($rs);
    if(!$content) {
        $modx->webAlertAndQuit($_lang["access_permission_denied"]);
    }

    /**
     * "General" tab setup
     */
    // Get Creator's username
    $rs = $modx->getDatabase()->select('username', $tbl_manager_users, "id='{$content['createdby']}'");
    $createdbyname = $modx->getDatabase()->getValue($rs);

    // Get Editor's username
    $rs = $modx->getDatabase()->select('username', $tbl_manager_users, "id='{$content['editedby']}'");
    $editedbyname = $modx->getDatabase()->getValue($rs);

    // Get Template name
    $rs = $modx->getDatabase()->select('templatename', $tbl_site_templates, "id='{$content['template']}'");
    $templatename = $modx->getDatabase()->getValue($rs);

    // Set the item name for logger
    $_SESSION['itemname'] = $content['pagetitle'];

    /**
     * "View Children" tab setup
     */
    $maxpageSize = $modx->getConfig('number_of_results');
    define('MAX_DISPLAY_RECORDS_NUM', $maxpageSize);

    // Get child document count
    $rs = $modx->getDatabase()->select('count(DISTINCT sc.id)', "{$tbl_site_content} AS sc
		LEFT JOIN {$tbl_document_groups} AS dg ON dg.document = sc.id", "sc.parent='{$content['id']}' AND ({$access})");
    $numRecords = $modx->getDatabase()->getValue($rs);

    $sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'createdon';
    $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'DESC';

    // Get child documents (with paging)
    $rs = $modx->getDatabase()->select('DISTINCT sc.*', "{$tbl_site_content} AS sc
		LEFT JOIN {$tbl_document_groups} AS dg ON dg.document = sc.id", "sc.parent='{$content['id']}' AND ({$access})", "{$sort} {$dir}", $modx->getMakeTable()->handlePaging() // add limit clause
    );
    $filter_sort = '';
    $filter_dir = '';
    if($numRecords > 0) {
        $filter_sort = '<select size="1" name="sort" class="form-control form-control-sm" onchange="document.location=\'index.php?a=3&id=' . $id . '&dir=' . $dir . '&sort=\'+this.options[this.selectedIndex].value">' . '<option value="createdon"' . (($sort == 'createdon') ? ' selected' : '') . '>' . $_lang['createdon'] . '</option>' . '<option value="pub_date"' . (($sort == 'pub_date') ? ' selected' : '') . '>' . $_lang["page_data_publishdate"] . '</option>' . '<option value="pagetitle"' . (($sort == 'pagetitle') ? ' selected' : '') . '>' . $_lang['pagetitle'] . '</option>' . '<option value="menuindex"' . (($sort == 'menuindex') ? ' selected' : '') . '>' . $_lang['resource_opt_menu_index'] . '</option>' . //********  resource_opt_is_published - //
            '<option value="published"' . (($sort == 'published') ? ' selected' : '') . '>' . $_lang['resource_opt_is_published'] . '</option>' . //********//
            '</select>';
        $filter_dir = '<select size="1" name="dir" class="form-control form-control-sm" onchange="document.location=\'index.php?a=3&id=' . $id . '&sort=' . $sort . '&dir=\'+this.options[this.selectedIndex].value">' . '<option value="DESC"' . (($dir == 'DESC') ? ' selected' : '') . '>' . $_lang['sort_desc'] . '</option>' . '<option value="ASC"' . (($dir == 'ASC') ? ' selected' : '') . '>' . $_lang['sort_asc'] . '</option>' . '</select>';
        $resource = $modx->getDatabase()->makeArray($rs);

        // CSS style for table
        //	$tableClass = 'grid';
        //	$rowHeaderClass = 'gridHeader';
        //	$rowRegularClass = 'gridItem';
        //	$rowAlternateClass = 'gridAltItem';
        $tableClass = 'table data nowrap';
        $columnHeaderClass = array(
            'text-center',
            'text-left',
            'text-center',
            'text-center',
            'text-center',
            'text-center'
        );


        $modx->getMakeTable()->setTableClass($tableClass);
        $modx->getMakeTable()->setColumnHeaderClass($columnHeaderClass);
        //	$modx->getMakeTable()->setRowHeaderClass($rowHeaderClass);
        //	$modx->getMakeTable()->setRowRegularClass($rowRegularClass);
        //	$modx->getMakeTable()->setRowAlternateClass($rowAlternateClass);

        // Table header
        $listTableHeader = array(
            'docid' => $_lang['id'],
            'title' => $_lang['resource_title'],
            'createdon' => $_lang['createdon'],
            'pub_date' => $_lang['page_data_publishdate'],
            'status' => $_lang['page_data_status'],
            'edit' => $_lang['mgrlog_action'],
        );
        $tbWidth = array(
            '1%',
            '',
            '1%',
            '1%',
            '1%',
            '1%'
        );
        $modx->getMakeTable()->setColumnWidths($tbWidth);

        $sd = isset($_REQUEST['dir']) ? '&amp;dir=' . $_REQUEST['dir'] : '&amp;dir=DESC';
        $sb = isset($_REQUEST['sort']) ? '&amp;sort=' . $_REQUEST['sort'] : '&amp;sort=createdon';
        $pg = isset($_REQUEST['page']) ? '&amp;page=' . (int) $_REQUEST['page'] : '';
        $add_path = $sd . $sb . $pg;

        $icons = array(
            'text/html' => $_style['tree_page_html'],
            'text/plain' => $_style['tree_page'],
            'text/xml' => $_style['tree_page_xml'],
            'text/css' => $_style['tree_page_css'],
            'text/javascript' => $_style['tree_page_js'],
            'application/rss+xml' => $_style['tree_page_rss'],
            'application/pdf' => $_style['tree_page_pdf'],
            'application/vnd.ms-word' => $_style['tree_page_word'],
            'application/vnd.ms-excel' => $_style['tree_page_excel'],
            'image/gif' => $_style['tree_page_gif'],
            'image/jpg' => $_style['tree_page_jpg'],
            'image/png' => $_style['tree_page_png']
        );

        $listDocs = array();
        foreach($resource as $k => $children) {

            switch($children['id']) {
                case $modx->getConfig('site_start')            :
                    $icon = $_style['tree_page_home'];
                    break;
                case $modx->getConfig('error_page')            :
                    $icon = $_style['tree_page_404'];
                    break;
                case $modx->getConfig('site_unavailable_page') :
                    $icon = $_style['tree_page_hourglass'];
                    break;
                case $modx->getConfig('unauthorized_page')     :
                    $icon = $_style['tree_page_info'];
                    break;
                default:
                    if($children['isfolder']) {
                        $icon = $_style['tree_folder_new'];
                    } else {
                        if(isset($icons[$children['contentType']])) {
                            $icon = $icons[$children['contentType']];
                        } else {
                            $icon = $_style['tree_page'];
                        }
                    }
            }

            $private = ($children['privateweb'] || $children['privatemgr'] ? ' private' : '');

            // дописываем в заголовок класс для неопубликованных плюс по всем ссылкам обратный путь
            // для сохранения сортировки
            $class = ($children['deleted'] ? 'text-danger text-decoration-through' : (!$children['published'] ? ' font-italic text-muted' : ' publish'));
            //$class .= ($children['hidemenu'] ? ' text-muted' : ' text-primary');
            //$class .= ($children['isfolder'] ? ' font-weight-bold' : '');
            if($modx->hasPermission('edit_document')) {
                $title = '<span class="doc-item' . $private . '">' . $icon . '<a href="index.php?a=27&amp;id=' . $children['id'] . $add_path . '">' . '<span class="' . $class . '">' . entities($children['pagetitle'], $modx->getConfig('modx_charset')) . '</span></a></span>';
            } else {
                $title = '<span class="doc-item' . $private . '">' . $icon . '<span class="' . $class . '">' . entities($children['pagetitle'], $modx->getConfig('modx_charset')) . '</span></span>';
            }

            $icon_pub_unpub = (!$children['published']) ? '<a href="index.php?a=61&amp;id=' . $children['id'] . $add_path . '" title="' . $_lang["publish_resource"] . '"><i class="' . $_style["icons_publish_document"] . '"></i></a>' : '<a href="index.php?a=62&amp;id=' . $children['id'] . $add_path . '" title="' . $_lang["unpublish_resource"] . '"><i class="' . $_style["icons_unpublish_resource"] . '" ></i></a>';

            $icon_del_undel = (!$children['deleted']) ? '<a onclick="return confirm(\'' . $_lang["confirm_delete_resource"] . '\')" href="index.php?a=6&amp;id=' . $children['id'] . $add_path . '" title="' . $_lang['delete_resource'] . '"><i class="' . $_style["icons_delete_resource"] . '"></i></a>' : '<a onclick="return confirm(\'' . $_lang["confirm_undelete"] . '\')" href="index.php?a=63&amp;id=' . $children['id'] . $add_path . '" title="' . $_lang['undelete_resource'] . '"><i class="' . $_style["icons_undelete_resource"] . '"></i></a>';

            $listDocs[] = array(
                'docid' => '<div class="text-right">' . $children['id'] . '</div>',
                'title' => $title,
                'createdon' => '<div class="text-right">' . ($modx->toDateFormat($children['createdon'] + $modx->timestamp(0), 'dateOnly')) . '</div>',
                'pub_date' => '<div class="text-right">' . ($children['pub_date'] ? ($modx->toDateFormat($children['pub_date'] + $modx->timestamp(0), 'dateOnly')) : '') . '</div>',
                'status' => '<div class="text-nowrap">' . ($children['published'] == 0 ? '<span class="unpublishedDoc">' . $_lang['page_data_unpublished'] . '</span>' : '<span class="publishedDoc">' . $_lang['page_data_published'] . '</span>') . '</div>',
                'edit' => '<div class="actions text-center text-nowrap">' . ($modx->hasPermission('edit_document') ? '<a href="index.php?a=27&amp;id=' . $children['id'] . $add_path . '" title="' . $_lang['edit'] . '"><i class="' . $_style["icons_edit_resource"] . '"></i></a><a href="index.php?a=51&amp;id=' . $children['id'] . $add_path . '" title="' . $_lang['move'] . '"><i
				class="' . $_style["icons_move_document"] . '"></i></a>' . $icon_pub_unpub : '') . ($modx->hasPermission('delete_document') ? $icon_del_undel : '') . '</div>'
            );
        }

        $modx->getMakeTable()->createPagingNavigation($numRecords, 'a=3&id=' . $content['id'] . '&dir=' . $dir . '&sort=' . $sort);
        $children_output = $modx->getMakeTable()->create($listDocs, $listTableHeader, 'index.php?a=3&amp;id=' . $content['id']);
    } else {
        // No Child documents
        $children_output = '<div class="container"><p>' . $_lang['resources_in_container_no'] . '</p></div>';
        $add_path = '';
    }
    ?>
    <script type="text/javascript">
        var actions = {
            new: function() {
                document.location.href = "index.php?pid=<?= $_REQUEST['id'] ?>&a=4";
            },
            newlink: function() {
                document.location.href = "index.php?pid=<?= $_REQUEST['id'] ?>&a=72";
            },
            edit: function() {
                document.location.href = "index.php?id=<?= $_REQUEST['id'] ?>&a=27";
            },
            save: function() {
                documentDirty = false;
                form_save = true;
                document.mutate.save.click();
            },
            delete: function() {
                if(confirm("{{ ManagerTheme::getLexicon('confirm_delete_resource') }}") === true) {
                    document.location.href = "index.php?id=<?= $_REQUEST['id'] ?>&a=6";
                }
            },
            cancel: function() {
                documentDirty = false;
                document.location.href = 'index.php?<?=($id == 0 ? 'a=2' : 'a=3&r=1&id=' . $id . $add_path) ?>';
            },
            move: function() {
                document.location.href = "index.php?id=<?= $_REQUEST['id'] ?>&a=51";
            },
            duplicate: function() {
                if(confirm("{{ ManagerTheme::getLexicon('confirm_resource_duplicate') }}") === true) {
                    document.location.href = "index.php?id=<?= $_REQUEST['id'] ?>&a=94";
                }
            },
            view: function() {
                window.open('<?= ($modx->getConfig('friendly_urls')) ? UrlProcessor::makeUrl($id) : MODX_SITE_URL . 'index.php?id=' . $id ?>', 'previeWin');
            }
        };

    </script>
    <script type="text/javascript" src="media/script/tablesort.js"></script>

    <h1>
        <i class="fa fa-info"></i><?= entities(iconv_substr($content['pagetitle'], 0, 50, $modx->getConfig('modx_charset')), $modx->getConfig('modx_charset')) . (iconv_strlen($content['pagetitle'], $modx->getConfig('modx_charset')) > 50 ? '...' : '') . ' <small>(' . (int)$_REQUEST['id'] . ')</small>' ?>
    </h1>

    <?= $_style['actionbuttons']['static']['document'] ?>


    <div class="tab-pane" id="childPane">
        <script type="text/javascript">
            docSettings = new WebFXTabPane(document.getElementById("childPane"), <?= ($modx->getConfig('remember_last_tab') ? 'true' : 'false') ?> );
        </script>

        <!-- General -->
        <div class="tab-page" id="tabdocGeneral">
            <h2 class="tab">{{ ManagerTheme::getLexicon('settings_general') }}</h2>
            <script type="text/javascript">docSettings.addTabPage(document.getElementById("tabdocGeneral"));</script>
            <div class="container container-body">
                <table>
                    <tr>
                        <td colspan="2"><b>{{ ManagerTheme::getLexicon('page_data_general') }}</b></td>
                    </tr>
                    <tr>
                        <td width="200" valign="top">{{ ManagerTheme::getLexicon('resource_title') }}:</td>
                        <td><b><?= entities($content['pagetitle']) ?></b></td>
                    </tr>
                    <tr>
                        <td width="200" valign="top">{{ ManagerTheme::getLexicon('long_title') }}:</td>
                        <td>
                            <small><?= $content['longtitle'] != '' ? entities($content['longtitle'], $modx->getConfig('modx_charset')) : "(<i>" . $_lang['not_set'] . "</i>)" ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">{{ ManagerTheme::getLexicon('resource_description') }}:</td>
                        <td><?= $content['description'] != '' ? entities($content['description'], $modx->getConfig('modx_charset')) : "(<i>" . $_lang['not_set'] . "</i>)" ?></td>
                    </tr>
                    <tr>
                        <td valign="top">{{ ManagerTheme::getLexicon('resource_summary') }}:</td>
                        <td><?= $content['introtext'] != '' ? entities($content['introtext'], $modx->getConfig('modx_charset')) : "(<i>" . $_lang['not_set'] . "</i>)" ?></td>
                    </tr>
                    <tr>
                        <td valign="top">{{ ManagerTheme::getLexicon('type') }}:</td>
                        <td><?= $content['type'] == 'reference' ? $_lang['weblink'] : $_lang['resource'] ?></td>
                    </tr>
                    <tr>
                        <td valign="top">{{ ManagerTheme::getLexicon('resource_alias') }}:</td>
                        <td><?= $content['alias'] != '' ? entities($content['alias'], $modx->getConfig('modx_charset')) : "(<i>" . $_lang['not_set'] . "</i>)" ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>{{ ManagerTheme::getLexicon('page_data_changes') }}</b></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_created') }}:</td>
                        <td><?= $modx->toDateFormat($content['createdon'] + $modx->timestamp(0)) ?> (<b><?= entities($createdbyname, $modx->getConfig('modx_charset')) ?></b>)
                        </td>
                    </tr>
                    <?php if($editedbyname != '') { ?>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_edited') }}:</td>
                        <td><?= $modx->toDateFormat($content['editedon'] + $modx->timestamp(0)) ?> (<b><?= entities($editedbyname, $modx->getConfig('modx_charset')) ?></b>)
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>{{ ManagerTheme::getLexicon('page_data_status') }}</b></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_status') }}:</td>
                        <td><?= $content['published'] == 0 ? '<span class="unpublishedDoc">' . $_lang['page_data_unpublished'] . '</span>' : '<span class="publisheddoc">' . $_lang['page_data_published'] . '</span>' ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_publishdate') }}:</td>
                        <td><?= $content['pub_date'] == 0 ? "(<i>" . $_lang['not_set'] . "</i>)" : $modx->toDateFormat($content['pub_date']) ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_unpublishdate') }}:</td>
                        <td><?= $content['unpub_date'] == 0 ? "(<i>" . $_lang['not_set'] . "</i>)" : $modx->toDateFormat($content['unpub_date']) ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_cacheable') }}:</td>
                        <td><?= $content['cacheable'] == 0 ? $_lang['no'] : $_lang['yes'] ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_searchable') }}:</td>
                        <td><?= $content['searchable'] == 0 ? $_lang['no'] : $_lang['yes'] ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('resource_opt_menu_index') }}:</td>
                        <td><?= entities($content['menuindex'], $modx->getConfig('modx_charset')) ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('resource_opt_show_menu') }}:</td>
                        <td><?= $content['hidemenu'] == 1 ? $_lang['no'] : $_lang['yes'] ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_web_access') }}:</td>
                        <td><?= $content['privateweb'] == 0 ? $_lang['public'] : '<b style="color: #821517">' . $_lang['private'] . '</b> ' . $_style["icons_secured"] ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_mgr_access') }}:</td>
                        <td><?= $content['privatemgr'] == 0 ? $_lang['public'] : '<b style="color: #821517">' . $_lang['private'] . '</b> ' . $_style["icons_secured"] ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>{{ ManagerTheme::getLexicon('page_data_markup') }}</b></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_template') }}:</td>
                        <td><?= entities($templatename, $modx->getConfig('modx_charset')) ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_editor') }}:</td>
                        <td><?= $content['richtext'] == 0 ? $_lang['no'] : $_lang['yes'] ?></td>
                    </tr>
                    <tr>
                        <td>{{ ManagerTheme::getLexicon('page_data_folder') }}:</td>
                        <td><?= $content['isfolder'] == 0 ? $_lang['no'] : $_lang['yes'] ?></td>
                    </tr>
                </table>
            </div>
        </div><!-- end tab-page -->

        <!-- View Children -->
        <div class="tab-page" id="tabChildren">
            <h2 class="tab">{{ ManagerTheme::getLexicon('view_child_resources_in_container') }}</h2>
            <script type="text/javascript">docSettings.addTabPage(document.getElementById("tabChildren"));</script>
            <div class="container container-body">
                <div class="form-group clearfix">
                    <?php if($numRecords > 0) : ?>
                    <div class="float-xs-left">
                        <span class="publishedDoc"><?= $numRecords . ' ' . $_lang['resources_in_container'] ?> (<strong><?= entities($content['pagetitle'], $modx->getConfig('modx_charset')) ?></strong>)</span>
                    </div>
                    <?php endif; ?>
                    <div class="float-xs-right">
                        <?= $filter_sort . ' ' . $filter_dir ?>
                    </div>

                </div>
                <div class="row">
                    <div class="table-responsive"><?= $children_output ?></div>
                </div>
            </div>
        </div><!-- end tab-page -->

    @if($modx->getConfig('cache_type'))
        <!-- Page Source -->
        <div class="tab-page" id="tabSource">
            <h2 class="tab">{{ ManagerTheme::getLexicon('page_data_source') }}</h2>
            <script type="text/javascript">docSettings.addTabPage(document.getElementById("tabSource"));</script>
            <?php
            $buffer = "";
            $filename = MODX_BASE_PATH . "assets/cache/docid_" . $id . ".pageCache.php";
            $handle = @fopen($filename, "r");
            if(!$handle) {
                $buffer = '<div class="container container-body">' . $_lang['page_data_notcached'] . '</div>';
            } else {
                while(!feof($handle)) {
                    $buffer .= fgets($handle, 4096);
                }
                fclose($handle);
                $buffer = '<div class="navbar navbar-editor">' . $_lang['page_data_cached'] . '</div><div class="section-editor clearfix"><textarea rows="20" wrap="soft">' . $modx->getPhpCompat()->htmlspecialchars($buffer) . "</textarea></div>\n";
            }
            echo $buffer;
            ?>
        </div><!-- end tab-page -->
    @endif

    </div><!-- end documentPane -->

    @if(is_numeric(get_by_key($_GET, 'tab')))
        <script type="text/javascript"> docSettings.setSelectedIndex({{ $_GET['tab'] }});</script>
    @endif

    @if(!empty($show_preview))
        <div class="sectionHeader">{{ ManagerTheme::getLexicon('preview') }}</div>
        <div class="sectionBody" id="lyr2">
            <iframe src="{{ MODX_SITE_URL }}index.php?id={{ $id }}&z=manprev" frameborder="0" border="0" id="previewIframe"></iframe>
        </div>
    @endif
@endsection
