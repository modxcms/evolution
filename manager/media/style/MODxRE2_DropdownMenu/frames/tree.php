<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}

// invoke OnManagerTreeInit event
$evtOut = $modx->invokeEvent('OnManagerTreeInit', $_REQUEST);
if(is_array($evtOut)) {
	echo implode("\n", $evtOut);
}
?>

<div class="treeframebody">
	<div id="treeMenu">

		<a class="treeButton" id="treeMenu_expandtree" onclick="modx.tree.expandTree();" title="<?php echo $_lang['expand_tree']; ?>"><?php echo $_style['expand_tree']; ?></a>

		<a class="treeButton" id="treeMenu_collapsetree" onclick="modx.tree.collapseTree();" title="<?php echo $_lang['collapse_tree']; ?>"><?php echo $_style['collapse_tree']; ?></a>

		<?php if($modx->hasPermission('new_document')) { ?>
			<a class="treeButton" id="treeMenu_addresource" onclick="top.main.document.location.href='index.php?a=4';" title="<?php echo $_lang['add_resource']; ?>"><?php echo $_style['add_doc_tree']; ?></a>
			<a class="treeButton" id="treeMenu_addweblink" onclick="top.main.document.location.href='index.php?a=72';" title="<?php echo $_lang['add_weblink']; ?>"><?php echo $_style['add_weblink_tree']; ?></a>
		<?php } ?>

		<a class="treeButton" id="treeMenu_refreshtree" onclick="modx.tree.restoreTree();" title="<?php echo $_lang['refresh_tree']; ?>"><?php echo $_style['refresh_tree']; ?></a>

		<a class="treeButton" id="treeMenu_sortingtree" onclick="modx.tree.showSorter(event);" title="<?php echo $_lang['sort_tree']; ?>"><?php echo $_style['sort_tree']; ?></a>

		<?php if($modx->hasPermission('edit_document')) { ?>
			<a class="treeButton" id="treeMenu_sortingindex" onclick="top.main.document.location.href='index.php?a=56&id=0';" title="<?php echo $_lang['sort_menuindex']; ?>"><?php echo $_style['sort_menuindex']; ?></a>
		<?php } ?>

		<?php if($use_browser && $modx->hasPermission('assets_images')) { ?>
			<a class="treeButton" id="treeMenu_openimages" title="<?php echo $_lang["images_management"] . "\n" . $_lang['em_button_shift'] ?>"><?php echo $_style['images_management']; ?></a>
		<?php } ?>

		<?php if($use_browser && $modx->hasPermission('assets_files')) { ?>
			<a class="treeButton" id="treeMenu_openfiles" title="<?php echo $_lang["files_management"] . "\n" . $_lang['em_button_shift'] ?>"><?php echo $_style['files_management']; ?></a>
		<?php } ?>

		<?php if($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin')) { ?>
			<a class="treeButton" id="treeMenu_openelements" title="<?php echo $_lang["element_management"] . "\n" . $_lang['em_button_shift'] ?>"><?php echo $_style['element_management']; ?></a>
		<?php } ?>

		<?php if($modx->hasPermission('empty_trash')) { ?>
			<a class="treeButton treeButtonDisabled" id="treeMenu_emptytrash" title="<?php echo $_lang['empty_recycle_bin_empty']; ?>"><?php echo $_style['empty_recycle_bin_empty']; ?></a>
		<?php } ?>

		<a class="treeButton<?php echo (isset($_COOKIE['MODX_themeColor']) && $_COOKIE['MODX_themeColor'] == 'dark' ? ' rotate180' : '') ?>" id="treeMenu_theme_dark" onclick="modx.tree.toggleTheme(event)"><i class="fa fa-adjust"></i></a>

	</div>

	<div id="treeHolder">
		<?php
		// invoke OnManagerTreePrerender event
		$evtOut = $modx->invokeEvent('OnManagerTreePrerender', $modx->db->escape($_REQUEST));
		if(is_array($evtOut)) {
			echo implode("\n", $evtOut);
		}
		?>
		<!--<div>-->
			<div class="rootNode" onclick="modx.tree.treeAction(event, 0, '<?php $site_name = htmlspecialchars($site_name, ENT_QUOTES, $modx->config['modx_charset']);
			echo $site_name; ?>');"><?php echo $_style['tree_showtree']; ?>&nbsp;<b><?php echo $site_name; ?></b>
			</div>
			<div id="treeRoot"></div>
		<!--</div>-->
		<?php
		// invoke OnManagerTreeRender event
		$evtOut = $modx->invokeEvent('OnManagerTreeRender', $modx->db->escape($_REQUEST));
		if(is_array($evtOut)) {
			echo implode("\n", $evtOut);
		}
		?>
	</div>
</div>
