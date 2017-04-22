<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}

// invoke OnTreePrerender event
$evtOut = $modx->invokeEvent('OnManagerTreeInit', $_REQUEST);
if(is_array($evtOut)) {
	echo implode("\n", $evtOut);
}
?>

	<div class="treeframebody">
		<div id="treeSplitter"></div>

		<table id="treeMenu" width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<a class="treeButton" id="Button1" onclick="modx.tree.expandTree();" title="<?php echo $_lang['expand_tree']; ?>"><?php echo $_style['expand_tree']; ?></a>
							</td>
							<td>
								<a class="treeButton" id="Button2" onclick="modx.tree.collapseTree();" title="<?php echo $_lang['collapse_tree']; ?>"><?php echo $_style['collapse_tree']; ?></a>
							</td>
							<?php if($modx->hasPermission('new_document')) { ?>
								<td>
									<a class="treeButton" id="Button3a" onclick="top.main.document.location.href='index.php?a=4';" title="<?php echo $_lang['add_resource']; ?>"><?php echo $_style['add_doc_tree']; ?></a>
								</td>
								<td>
									<a class="treeButton" id="Button3c" onclick="top.main.document.location.href='index.php?a=72';" title="<?php echo $_lang['add_weblink']; ?>"><?php echo $_style['add_weblink_tree']; ?></a>
								</td>
							<?php } ?>
							<td>
								<a class="treeButton" id="Button4" onclick="modx.tree.restoreTree();" title="<?php echo $_lang['refresh_tree']; ?>"><?php echo $_style['refresh_tree']; ?></a>
							</td>
							<td>
								<a class="treeButton" id="Button5" onclick="modx.tree.showSorter();" title="<?php echo $_lang['sort_tree']; ?>"><?php echo $_style['sort_tree']; ?></a>
							</td>
							<?php if($modx->hasPermission('edit_document')) { ?>
								<td>
									<a id="Button11" class="treeButton" onclick="top.main.document.location.href='index.php?a=56&id=0';" title="<?php echo $_lang['sort_menuindex']; ?>"><?php echo $_style['sort_menuindex']; ?></a>
								</td>
							<?php } ?>
							<?php if($use_browser && $modx->hasPermission('assets_images')) { ?>
								<td>
									<a id="treeMenu_openimages" class="treeButton" title="<?php echo $_lang["images_management"] . "\n" . $_lang['em_button_shift'] ?>"><?php echo $_style['images_management']; ?></a>
								</td>
							<?php } ?>
							<?php if($use_browser && $modx->hasPermission('assets_files')) { ?>
								<td>
									<a id="treeMenu_openfiles" class="treeButton" title="<?php echo $_lang["files_management"] . "\n" . $_lang['em_button_shift'] ?>"><?php echo $_style['files_management']; ?></a>
								</td>
							<?php } ?>
							<?php if($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin')) { ?>
								<td>
									<a id="Button12" class="treeButton" title="<?php echo $_lang["element_management"] . "\n" . $_lang['em_button_shift'] ?>"><?php echo $_style['element_management']; ?></a>
								</td>
							<?php } ?>
							<?php if($modx->hasPermission('empty_trash')) { ?>
								<td>
									<a id="Button10" class="treeButtonDisabled" title="<?php echo $_lang['empty_recycle_bin_empty']; ?>"><?php echo $_style['empty_recycle_bin_empty']; ?></a>
								</td>
							<?php } ?>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<?php if($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin')) { ?>
			<script>
				$('#Button12').click(function(e) {
					e.preventDefault();
					var randomNum = 'gener1';
					if(e.shiftKey) {
						randomNum = Math.floor((Math.random() * 999999) + 1);
					}
					modx.openWindow({
						url: 'index.php?a=76',
						title: randomNum
					})
				});
			</script>
		<?php } ?>

		<?php if($use_browser && $modx->hasPermission('assets_images')) { ?>
			<script>
				$('#treeMenu_openimages').click(function(e) {
					e.preventDefault();
					var randomNum = 'gener2';
					if(e.shiftKey) {
						randomNum = Math.floor((Math.random() * 999999) + 1);
					}
					modx.openWindow({
						url: 'media/browser/<?php echo $which_browser; ?>/browse.php?&type=images',
						title: randomNum
					})
				});
			</script>
		<?php } ?>

		<?php if($use_browser && $modx->hasPermission('assets_files')) { ?>
			<script>
				$('#treeMenu_openfiles').click(function(e) {
					e.preventDefault();
					var randomNum = 'gener3';
					if(e.shiftKey) {
						randomNum = Math.floor((Math.random() * 999999) + 1);
					}
					modx.openWindow({
						url: 'media/browser/<?php echo $which_browser; ?>/browse.php?&type=files',
						title: randomNum
					})
				});
			</script>
		<?php } ?>

		<div id="floater">
			<?php
			$sortParams = array(
				'tree_sortby',
				'tree_sortdir',
				'tree_nodename'
			);
			foreach($sortParams as $param) {
				if(isset($_REQUEST[$param])) {
					$modx->manager->saveLastUserSetting($param, $_REQUEST[$param]);
					$_SESSION[$param] = $_REQUEST[$param];
				} else if(!isset($_SESSION[$param])) {
					$_SESSION[$param] = $modx->manager->getLastUserSetting($param);
				}
			}
			?>
			<form name="sortFrm" id="sortFrm">
				<input type="hidden" name="dt" value="<?php echo htmlspecialchars($_REQUEST['dt']); ?>" />
				<div>
					<?php echo $_lang["sort_tree"] ?>
					<select name="sortby">
						<option value="isfolder" <?php echo $_SESSION['tree_sortby'] == 'isfolder' ? "selected='selected'" : "" ?>><?php echo $_lang['folder']; ?></option>
						<option value="pagetitle" <?php echo $_SESSION['tree_sortby'] == 'pagetitle' ? "selected='selected'" : "" ?>><?php echo $_lang['pagetitle']; ?></option>
						<option value="longtitle" <?php echo $_SESSION['tree_sortby'] == 'longtitle' ? "selected='selected'" : "" ?>><?php echo $_lang['long_title']; ?></option>
						<option value="id" <?php echo $_SESSION['tree_sortby'] == 'id' ? "selected='selected'" : "" ?>><?php echo $_lang['id']; ?></option>
						<option value="menuindex" <?php echo $_SESSION['tree_sortby'] == 'menuindex' ? "selected='selected'" : "" ?>><?php echo $_lang['resource_opt_menu_index'] ?></option>
						<option value="createdon" <?php echo $_SESSION['tree_sortby'] == 'createdon' ? "selected='selected'" : "" ?>><?php echo $_lang['createdon']; ?></option>
						<option value="editedon" <?php echo $_SESSION['tree_sortby'] == 'editedon' ? "selected='selected'" : "" ?>><?php echo $_lang['editedon']; ?></option>
						<option value="publishedon" <?php echo $_SESSION['tree_sortby'] == 'publishedon' ? "selected='selected'" : "" ?>><?php echo $_lang['page_data_publishdate']; ?></option>
					</select>
				</div>
				<div>
					<select name="sortdir">
						<option value="DESC" <?php echo $_SESSION['tree_sortdir'] == 'DESC' ? "selected='selected'" : "" ?>><?php echo $_lang['sort_desc']; ?></option>
						<option value="ASC" <?php echo $_SESSION['tree_sortdir'] == 'ASC' ? "selected='selected'" : "" ?>><?php echo $_lang['sort_asc']; ?></option>
					</select>
				</div>
				<div>
					<p><?php echo $_lang["setting_resource_tree_node_name"] ?></p>
					<select name="nodename">
						<option value="default" <?php echo $_SESSION['tree_nodename'] == 'default' ? "selected='selected'" : "" ?>><?php echo trim($_lang['default'], ':'); ?></option>
						<option value="pagetitle" <?php echo $_SESSION['tree_nodename'] == 'pagetitle' ? "selected='selected'" : "" ?>><?php echo $_lang['pagetitle']; ?></option>
						<option value="longtitle" <?php echo $_SESSION['tree_nodename'] == 'longtitle' ? "selected='selected'" : "" ?>><?php echo $_lang['long_title']; ?></option>
						<option value="menutitle" <?php echo $_SESSION['tree_nodename'] == 'menutitle' ? "selected='selected'" : "" ?>><?php echo $_lang['resource_opt_menu_title']; ?></option>
						<option value="alias" <?php echo $_SESSION['tree_nodename'] == 'alias' ? "selected='selected'" : "" ?>><?php echo $_lang['alias']; ?></option>
						<option value="createdon" <?php echo $_SESSION['tree_nodename'] == 'createdon' ? "selected='selected'" : "" ?>><?php echo $_lang['createdon']; ?></option>
						<option value="editedon" <?php echo $_SESSION['tree_nodename'] == 'editedon' ? "selected='selected'" : "" ?>><?php echo $_lang['editedon']; ?></option>
						<option value="publishedon" <?php echo $_SESSION['tree_nodename'] == 'publishedon' ? "selected='selected'" : "" ?>><?php echo $_lang['page_data_publishdate']; ?></option>
					</select>
				</div>
				<div>
					<ul class="actionButtons">
						<li>
							<a class="treeButton" id="button7" onclick="modx.tree.updateTree();modx.tree.showSorter();" title="<?php echo $_lang['sort_tree']; ?>"><?php echo $_lang['sort_tree']; ?></a>
						</li>
					</ul>
				</div>
			</form>
		</div>

		<div id="treeHolder">
			<?php
			// invoke OnTreeRender event
			$evtOut = $modx->invokeEvent('OnManagerTreePrerender', $modx->db->escape($_REQUEST));
			if(is_array($evtOut)) {
				echo implode("\n", $evtOut);
			}
			?>
			<div><?php echo $_style['tree_showtree']; ?>&nbsp;<span class="rootNode" onclick="modx.tree.treeAction(event, 0, '<?php $site_name = htmlspecialchars($site_name, ENT_QUOTES, $modx->config['modx_charset']);
				echo $site_name; ?>');"><b><?php echo $site_name; ?></b></span>
				<div id="treeRoot"></div>
			</div>
			<?php
			// invoke OnTreeRender event
			$evtOut = $modx->invokeEvent('OnManagerTreeRender', $modx->db->escape($_REQUEST));
			if(is_array($evtOut)) {
				echo implode("\n", $evtOut);
			}
			?>
		</div>

		<!-- Contextual Menu Popup Code -->
		<div id="mx_contextmenu" onselectstart="return false;">
			<div id="nameHolder">&nbsp;</div>
			<?php
			constructLink(3, $_style["ctx_new_document"], $_lang["create_resource_here"], $modx->hasPermission('new_document')); // new Resource
			constructLink(2, $_style["ctx_edit_document"], $_lang["edit_resource"], $modx->hasPermission('edit_document')); // edit
			constructLink(5, $_style["ctx_move_document"], $_lang["move_resource"], $modx->hasPermission('save_document')); // move
			constructLink(7, $_style["ctx_resource_duplicate"], $_lang["resource_duplicate"], $modx->hasPermission('new_document')); // duplicate
			constructLink(11, $_style["ctx_sort_menuindex"], $_lang["sort_menuindex"], $modx->hasPermission('edit_document')); // sort menu index
			?>
			<div class="seperator"></div>
			<?php
			constructLink(9, $_style["ctx_publish_document"], $_lang["publish_resource"], $modx->hasPermission('publish_document')); // publish
			constructLink(10, $_style["ctx_unpublish_resource"], $_lang["unpublish_resource"], $modx->hasPermission('publish_document')); // unpublish
			constructLink(4, $_style["ctx_delete"], $_lang["delete_resource"], $modx->hasPermission('delete_document')); // delete
			constructLink(8, $_style["ctx_undelete_resource"], $_lang["undelete_resource"], $modx->hasPermission('delete_document')); // undelete
			?>
			<div class="seperator"></div>
			<?php
			constructLink(6, $_style["ctx_weblink"], $_lang["create_weblink_here"], $modx->hasPermission('new_document')); // new Weblink
			?>
			<div class="seperator"></div>
			<?php
			constructLink(1, $_style["ctx_resource_overview"], $_lang["resource_overview"], $modx->hasPermission('view_document')); // view
			constructLink(12, $_style["ctx_preview_resource"], $_lang["preview_resource"], 1); // preview
			?>
		</div>
	</div>
<?php
function constructLink($action, $img, $text, $allowed) {
	if($allowed == 1) {
		echo sprintf('<div class="menuLink" id="item%s" onclick="modx.tree.menuHandler(%s); modx.tree.hideMenu();">', $action, $action);
		echo sprintf('<i class="%s"></i> %s</div>', $img, $text);
	}
}
