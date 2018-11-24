<?php
// invoke OnManagerTreeInit event
$evtOut = $modx->invokeEvent('OnManagerTreeInit', $_REQUEST);
if(is_array($evtOut)) {
    echo implode("\n", $evtOut);
}
?>

<div class="treeframebody">
    <div id="treeMenu">

        <a class="treeButton" id="treeMenu_expandtree" onclick="modx.tree.expandTree();" title="<?php echo $_lang['expand_tree']; ?>"><i class="fa fa-arrow-circle-down"></i></a>

        <a class="treeButton" id="treeMenu_collapsetree" onclick="modx.tree.collapseTree();" title="<?php echo $_lang['collapse_tree']; ?>"><i class="fa fa-arrow-circle-up"></i></a>

        <?php if($modx->hasPermission('new_document')) { ?>
        <a class="treeButton" id="treeMenu_addresource" onclick="modx.tabs({url:'<?= MODX_MANAGER_URL ?>?a=4', title: '<?php echo $_lang['add_resource']; ?>'});" title="<?php echo $_lang['add_resource']; ?>"><i class="fa fa-file"></i></a>
        <a class="treeButton" id="treeMenu_addweblink" onclick="modx.tabs({url:'<?= MODX_MANAGER_URL ?>?a=72', title: '<?php echo $_lang['add_weblink']; ?>'});" title="<?php echo $_lang['add_weblink']; ?>"><i class="fa fa-link"></i></a>
        <?php } ?>

        <a class="treeButton" id="treeMenu_refreshtree" onclick="modx.tree.restoreTree();" title="<?php echo $_lang['refresh_tree']; ?>"><i class="fa fa-refresh"></i></a>

        <a class="treeButton" id="treeMenu_sortingtree" onclick="modx.tree.showSorter(event);" title="<?php echo $_lang['sort_tree']; ?>"><i class="fa fa-sort"></i></a>

        <?php if($modx->hasPermission('edit_document') && $modx->hasPermission('save_document')) { ?>
        <a class="treeButton" id="treeMenu_sortingindex" onclick="modx.tabs({url: '<?= MODX_MANAGER_URL ?>?a=56&id=0', title: '<?php echo $_lang['sort_menuindex']; ?>'});" title="<?php echo $_lang['sort_menuindex']; ?>"><i class="fa fa-sort-numeric-asc"></i></a>
        <?php } ?>

        @if($modx->getConfig('use_browser') && $modx->hasPermission('assets_images'))
            <a class="treeButton" id="treeMenu_openimages" title="<?php echo $_lang["images_management"] . "\n" . $_lang['em_button_shift'] ?>"><i class="fa fa-camera"></i></a>
        @endif

        @if($modx->getConfig('use_browser') && $modx->hasPermission('assets_files'))
            <a class="treeButton" id="treeMenu_openfiles" title="<?php echo $_lang["files_management"] . "\n" . $_lang['em_button_shift'] ?>"><i class="fa fa-files-o"></i></a>
        @endif

        <?php if($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin')) { ?>
        <a class="treeButton" id="treeMenu_openelements" title="<?php echo $_lang["element_management"] . "\n" . $_lang['em_button_shift'] ?>"><i class="fa fa-th"></i></a>
        <?php } ?>

        <?php if($modx->hasPermission('empty_trash')) { ?>
        <a class="treeButton treeButtonDisabled" id="treeMenu_emptytrash" title="<?php echo $_lang['empty_recycle_bin_empty']; ?>"><i class="fa fa-trash-o"></i></a>
        <?php } ?>

        <a class="treeButton" id="treeMenu_theme_dark" onclick="modx.tree.toggleTheme(event)" title="<?php echo $_lang['manager_theme_mode_title']; ?>"><i class="fa fa-adjust"></i></a>

    </div>

    <div id="treeHolder">
        <?php
        // invoke OnManagerTreePrerender event
        $evtOut = $modx->invokeEvent('OnManagerTreePrerender', $modx->getDatabase()->escape($_REQUEST));
        if(is_array($evtOut)) {
            echo implode("\n", $evtOut);
        }
        $siteName = $modx->getPhpCompat()->entities($modx->getConfig('site_name'));
        ?>
        <div id="node0" class="rootNode"><a class="node" onclick="modx.tree.treeAction(event, 0)" data-id="0" data-title-esc="<?=$siteName?>"><span class="icon"><?php echo $_style['tree_showtree']; ?></span><span class="title"><?=$siteName?></span></a>
            <div id="treeloader"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
        </div>
        <div id="treeRoot"></div>
        <?php
        // invoke OnManagerTreeRender event
        $evtOut = $modx->invokeEvent('OnManagerTreeRender', $modx->getDatabase()->escape($_REQUEST));
        if(is_array($evtOut)) {
            echo implode("\n", $evtOut);
        }
        ?>
    </div>
</div>
