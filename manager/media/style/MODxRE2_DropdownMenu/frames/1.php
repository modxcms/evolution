<?php

if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
header("X-XSS-Protection: 0");

$_SESSION['browser'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 1') !== false) ? 'legacy_IE' : 'modern';

// invoke OnManagerPreFrameLoader
$modx->invokeEvent('OnManagerPreFrameLoader', array('action' => $action));

$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

if(!isset($modx->config['manager_menu_height'])) {
	$modx->config['manager_menu_height'] = 35;
}

if(!isset($modx->config['manager_tree_width'])) {
	$modx->config['manager_tree_width'] = 320;
}

if(isset($_SESSION['onLoginForwardToAction']) && is_int($_SESSION['onLoginForwardToAction'])) {
	$initMainframeAction = $_SESSION['onLoginForwardToAction'];
	unset($_SESSION['onLoginForwardToAction']);
} else {
	$initMainframeAction = 2; // welcome.static
}

if(!isset($_SESSION['tree_show_only_folders'])) {
	$_SESSION['tree_show_only_folders'] = 1;
}

$body_class = '';
$menu_height = $modx->config['manager_menu_height'];
$tree_width = $modx->config['manager_tree_width'];
$tree_min_width = 0;

if(isset($_COOKIE['MODX_positionSideBar'])) {
	$MODX_positionSideBar = $_COOKIE['MODX_positionSideBar'];
} else {
	$MODX_positionSideBar = $tree_width;
}

if(!$MODX_positionSideBar) {
	$body_class .= 'sidebar-closed';
}

if(isset($modx->pluginCache['ElementsInTree'])) {
	$body_class .= ' ElementsInTree';
}

$unlockTranslations = array(
	'msg' => $_lang["unlock_element_id_warning"],
	'type1' => $_lang["lock_element_type_1"],
	'type2' => $_lang["lock_element_type_2"],
	'type3' => $_lang["lock_element_type_3"],
	'type4' => $_lang["lock_element_type_4"],
	'type5' => $_lang["lock_element_type_5"],
	'type6' => $_lang["lock_element_type_6"],
	'type7' => $_lang["lock_element_type_7"],
	'type8' => $_lang["lock_element_type_8"]
);

foreach($unlockTranslations as $key => $value) {
	$unlockTranslations[$key] = iconv($modx->config["modx_charset"], "utf-8", $value);
}

$user = $modx->getUserInfo($modx->getLoginUserID());
if($user['which_browser'] == 'default') {
	$user['which_browser'] = $modx->config['which_browser'];
}
?>
<!DOCTYPE html>
<html <?php echo (isset($modx_textdir) && $modx_textdir ? 'dir="rtl" lang="' : 'lang="') . $mxla . '" xml:lang="' . $mxla . '"'; ?>>
<head>
	<title><?php echo $site_name ?>- (MODX CMS Manager)</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset ?>" />
	<link rel="stylesheet" type="text/css" href="media/style/common/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="media/style/common/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css" />
	<style>
		#tree { width: <?php echo $MODX_positionSideBar ?>px }
		#main, #resizer { left: <?php echo $MODX_positionSideBar ?>px }
	</style>
	<script src="media/script/jquery/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript">

		// GLOBAL variable modx
		var modx = {
			MGR_DIR: "<?php echo MGR_DIR ?>",
			MODX_SITE_URL: "<?php echo MODX_SITE_URL ?>",
			user: {
				username: "<?php echo $user['username'] ?>"
			},
			config: {
				mail_check_timeperiod: <?php echo $modx->config['mail_check_timeperiod'] ?>,
				menu_height: <?php echo (int) $menu_height ?>,
				tree_width: <?php echo (int) $tree_width ?>,
				tree_min_width: <?php echo (int) $tree_min_width ?>,
				session_timeout: <?php echo (int) $modx->config['session_timeout'] ?>,
				site_start: <?php echo (int) $modx->config['site_start'] ?>,
				tree_page_click: <?php echo(!empty($modx->config['tree_page_click']) ? (int) $modx->config['tree_page_click'] : 27); ?>,
				theme: "<?php echo $modx->config['manager_theme'] ?>",
				which_browser: "<?php echo $user['which_browser'] ?>",
				layout: <?php echo (int) $manager_layout ?>,
				textdir: "<?php echo $modx_textdir ?>",
			},
			lang: {
				already_deleted: "<?php echo $_lang['already_deleted'] ?>",
				collapse_tree: "<?php echo $_lang['collapse_tree'] ?>",
				confirm_delete_resource: "<?php echo $_lang['confirm_delete_resource'] ?>",
				confirm_empty_trash: "<?php echo $_lang['confirm_empty_trash'] ?>",
				confirm_publish: "<?php echo $_lang['confirm_publish'] ?>",
				confirm_remove_locks: "<?php echo $_lang['confirm_remove_locks'] ?>",
				confirm_resource_duplicate: "<?php echo $_lang['confirm_resource_duplicate'] ?>",
				confirm_undelete: "<?php echo $_lang['confirm_undelete'] ?>",
				confirm_unpublish: "<?php echo $_lang['confirm_unpublish'] ?>",
				empty_recycle_bin: "<?php echo $_lang['empty_recycle_bin'] ?>",
				empty_recycle_bin_empty: "<?php echo addslashes($_lang['empty_recycle_bin_empty']); ?>",
				expand_tree: "<?php echo $_lang['expand_tree'] ?>",
				inbox: "<?php echo $_lang['inbox'] ?>",
				loading_doc_tree: "<?php echo $_lang['loading_doc_tree'] ?>",
				loading_menu: "<?php echo $_lang['loading_menu'] ?>",
				not_deleted: "<?php echo $_lang['not_deleted'] ?>",
				unable_set_link: "<?php echo $_lang['unable_set_link'] ?>",
				unable_set_parent: "<?php echo $_lang['unable_set_parent'] ?>",
				working: "<?php echo $_lang['working'] ?>"
			},
			style: {
				actions_file: "<?php echo addslashes($_style['actions_file']) ?>",
				actions_pencil: "<?php echo addslashes($_style['actions_pencil']) ?>",
				actions_plus: "<?php echo addslashes($_style['actions_plus']) ?>",
				actions_reply: "<?php echo addslashes($_style['actions_reply']) ?>",
				collapse_tree: "<?php echo addslashes($_style['collapse_tree']) ?>",
				email: "<?php echo addslashes($_style['email']) ?>",
				empty_recycle_bin: "<?php echo addslashes($_style['empty_recycle_bin']) ?>",
				empty_recycle_bin_empty: "<?php echo addslashes($_style['empty_recycle_bin_empty']) ?>",
				expand_tree: "<?php echo addslashes($_style['expand_tree']) ?>",
				icons_external_link: "<?php echo addslashes($_style['icons_external_link']) ?>",
				icons_working: "<?php echo addslashes($_style['tree_working']) ?>",
				tree_info: "<?php echo addslashes($_style['tree_info']) ?>",
				tree_folder: "<?php echo addslashes($_style['tree_folder_new']) ?>",
				tree_folder_secure: "<?php echo addslashes($_style['tree_folder_secure']) ?>",
				tree_folderopen: "<?php echo addslashes($_style['tree_folderopen_new']) ?>",
				tree_folderopen_secure: "<?php echo addslashes($_style['tree_folderopen_secure']) ?>",
				tree_minusnode: "<?php echo addslashes($_style["tree_minusnode"]) ?>",
				tree_plusnode: "<?php echo addslashes($_style['tree_plusnode']) ?>",
				tree_preview_resource: "<?php echo addslashes($_style['tree_preview_resource']) ?>"
			},
			permission: {
				assets_images: <?php echo $modx->hasPermission('assets_images') ? 1 : 0 ?>,
				delete_document: <?php echo $modx->hasPermission('delete_document') ? 1 : 0 ?>,
				edit_chunk: <?php echo $modx->hasPermission('edit_chunk') ? 1 : 0 ?>,
				edit_plugin: <?php echo $modx->hasPermission('edit_plugin') ? 1 : 0 ?>,
				edit_snippet: <?php echo $modx->hasPermission('edit_snippet') ? 1 : 0 ?>,
				edit_template: <?php echo $modx->hasPermission('edit_template') ? 1 : 0 ?>,
				new_document: <?php echo $modx->hasPermission('new_document') ? 1 : 0 ?>,
				publish_document: <?php echo $modx->hasPermission('publish_document') ? 1 : 0 ?>
			},
			plugins: {
				ElementsInTree: <?php echo isset($modx->pluginCache['ElementsInTree']) ? 1 : 0 ?>,
				EVOmodal: <?php echo isset($modx->pluginCache['EVO.modal']) ? 1 : 0 ?>
			},
			extend: function(a, b) {
				for(var c in a) a[c] = a[c];
			},
			extended: function(a) {
				for(var b in a) this[b] = a[b]; delete a[b]
			},
			openedArray: [],
			lockedElementsTranslation: <?php echo json_encode($unlockTranslations) . "\n" ?>
		};
		<?php
		$opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
		echo (empty($opened) ? '' : 'modx.openedArray[' . implode("] = 1;\n		modx.openedArray[", $opened) . '] = 1;') . "\n";
		?>
	</script>
	<script src="media/style/<?php echo $modx->config['manager_theme'] ?>/modx.js"></script>
</head>
<body class="<?php echo $body_class ?>">
<div id="frameset">
	<div id="mainMenu" class="dropdown">
		<div class="col float-left">
			<input type="hidden" name="sessToken" id="sessTokenInput" value="<?php echo md5(session_id()); ?>" />
			<?php include('mainmenu.php'); ?>
		</div>
		<div class="col float-left">
			<div id="statusbar">
				<div id="buildText"></div>
				<div id="workText"></div>
			</div>
		</div>
		<div class="col float-right">
			<ul class="nav">
				<li id="searchform">
					<form action="index.php?a=71#results" method="post" target="main">
						<input type="hidden" value="Search" name="submitok" />
						<label for="searchid" class="label_searchid">
							<i class="fa fa-search"></i>
						</label>
						<input type="text" id="searchid" name="searchid" size="25" class="form-control input-sm">
						<div class="mask"></div>
					</form>
				</li>
				<li>
					<a href="../" target="_blank" title="<?php echo $_lang['preview'] ?>" onclick="setLastClickedElement(0,0);">
						<i class="fa fa-desktop"></i>
					</a>
				</li>
				<?php if($modx->hasPermission('settings') || $modx->hasPermission('view_eventlog') || $modx->hasPermission('logs') || $modx->hasPermission('help')) { ?>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" onclick="return false;"><i class="fa fa-sliders"></i></a>
						<ul class="dropdown-menu">
							<?php if($modx->hasPermission('settings')) { ?>
								<li>
									<a href="index.php?a=17" target="main" onclick="setLastClickedElement(0,0);">
										<i class="fa fa-cog fw"></i><?php echo $_lang['edit_settings'] ?>
									</a>
								</li>
							<?php } ?>
							<?php if($modx->hasPermission('view_eventlog')) { ?>
								<li>
									<a href="index.php?a=70" target="main" onclick="setLastClickedElement(0,0);">
										<i class="fa fa-calendar"></i><?php echo $_lang['site_schedule'] ?>
									</a>
								</li>
							<?php } ?>
							<?php if($modx->hasPermission('view_eventlog')) { ?>
								<li>
									<a href="index.php?a=114" target="main" onclick="setLastClickedElement(0,0);">
										<i class="fa fa-exclamation-triangle"></i><?php echo $_lang['eventlog_viewer'] ?>
									</a>
								</li>
							<?php } ?>
							<?php if($modx->hasPermission('logs')) { ?>
								<li>
									<a href="index.php?a=13" target="main" onclick="setLastClickedElement(0,0);">
										<i class="fa fa-user-secret"></i><?php echo $_lang['view_logging'] ?>
									</a>
								</li>
								<li>
									<a href="index.php?a=53" target="main" onclick="setLastClickedElement(0,0);">
										<i class="fa fa-info-circle"></i><?php echo $_lang['view_sysinfo'] ?>
									</a>
								</li>
							<?php } ?>
							<?php if($modx->hasPermission('help')) { ?>
								<li>
									<a href="index.php?a=9#version_notices" target="main" onclick="setLastClickedElement(0,0);">
										<i class="fa fa-question-circle"></i><?php echo $_lang['help'] ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
				<li class="dropdown account">
					<a href="javascript:;" class="dropdown-toggle" onclick="return false;">
						<div class="username"><?php echo $user['username'] ?></div>
						<?php if($user['photo']) { ?>
							<div class="icon photo" style="background-image: url(<?php echo MODX_SITE_URL . $user['photo'] ?>);"></div>
						<?php } else { ?>
							<div class="icon"><i class="fa fa-user-circle"></i></div>
						<?php } ?>
						<div id="msgCounter"></div>
					</a>
					<ul class="dropdown-menu">
						<li id="newMail"></li>
						<?php if($modx->hasPermission('change_password')) { ?>
							<li>
								<a onclick="" href="index.php?a=28" target="main">
									<i class="fa fa-lock"></i><?php echo $_lang['change_password'] ?>
								</a>
							</li>
						<?php } ?>
						<li>
							<a href="index.php?a=8">
								<i class="fa fa-sign-out"></i><?php echo $_lang['logout'] ?>
							</a>
						</li>
						<?php
						$style = $modx->config['settings_version'] != $modx->getVersionData('version') ? 'style="color:#ffff8a;"' : '';
						$version = stristr($modx->config['settings_version'], 'd') === FALSE ? 'MODX Evolution' : 'MODX EVO Custom';
						?>
						<?php
						echo sprintf('<li><span class="dropdown-item" title="%s &ndash; %s" %s>' . $version . ' %s</span></li>', $site_name, $modx->getVersionData('full_appname'), $style, $modx->config['settings_version']);
						?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div id="tree">
		<?php include('tree.php'); ?>
	</div>
	<div id="main">
		<iframe name="main" id="mainframe" src="index.php?a=<?php echo $initMainframeAction; ?>" scrolling="auto" frameborder="0" onload="modx.main.init()"></iframe>
	</div>
	<div id="resizer">
		<a id="hideMenu">
			<i class="fa fa-angle-left"></i>
		</a>
	</div>
	<div id="searchresult"></div>

	<div id="floater" class="dropdown">
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
			<p><?php echo $_lang["sort_tree"] ?></p>
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
			<select name="sortdir">
				<option value="DESC" <?php echo $_SESSION['tree_sortdir'] == 'DESC' ? "selected='selected'" : "" ?>><?php echo $_lang['sort_desc']; ?></option>
				<option value="ASC" <?php echo $_SESSION['tree_sortdir'] == 'ASC' ? "selected='selected'" : "" ?>><?php echo $_lang['sort_asc']; ?></option>
			</select>
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
			<p>
				<label><input type="checkbox" name="showonlyfolders" value="<?php echo($_SESSION['tree_show_only_folders'] ? 1 : '') ?>" onclick="this.value = this.value ? '' : 1;" <?php echo($_SESSION['tree_show_only_folders'] ? ' checked="checked"' : '') ?> /> <?php echo $_lang['view_child_resources_in_container'] ?>
				</label></p>
			<div>
				<ul class="actionButtons">
					<li>
						<a href="javascript:;" onclick="modx.tree.updateTree();modx.tree.showSorter(event);" title="<?php echo $_lang['sort_tree']; ?>"><?php echo $_lang['sort_tree']; ?></a>
					</li>
				</ul>
			</div>
		</form>
	</div>

	<!-- Contextual Menu Popup Code -->
	<div id="mx_contextmenu" class="dropdown" onselectstart="return false;">
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

	<?php
	function constructLink($action, $img, $text, $allowed) {
		if($allowed == 1) {
			echo sprintf('<div class="menuLink" id="item%s" onclick="modx.tree.menuHandler(%s);">', $action, $action);
			echo sprintf('<i class="%s"></i> %s</div>', $img, $text);
		}
	}

	?>

	<script type="text/javascript">
		document.getElementById('treeMenu_openelements').onclick = function(e) {
			e.preventDefault();
			var randomNum = '<?php echo $_lang["elements"] ?>';
			if(e.shiftKey) {
				randomNum += ' #' + Math.floor((Math.random() * 999999) + 1);
			}
			modx.openWindow({
				url: 'index.php?a=76',
				title: randomNum
			})
		};
		document.getElementById('treeMenu_openimages').onclick = function(e) {
			e.preventDefault();
			var randomNum = '<?php echo $_lang["files_files"] ?>';
			if(e.shiftKey) {
				randomNum += ' #' + Math.floor((Math.random() * 999999) + 1);
			}
			modx.openWindow({
				url: 'media/browser/<?php echo $which_browser; ?>/browse.php?&type=images',
				title: randomNum
			})
		};
		document.getElementById('treeMenu_openfiles').onclick = function(e) {
			e.preventDefault();
			var randomNum = '<?php echo $_lang["files_files"] ?>';
			if(e.shiftKey) {
				randomNum += ' #' + Math.floor((Math.random() * 999999) + 1);
			}
			modx.openWindow({
				url: 'media/browser/<?php echo $which_browser; ?>/browse.php?&type=files',
				title: randomNum
			})
		};
	</script>

	<?php
	// invoke OnManagerFrameLoader
	$modx->invokeEvent('OnManagerFrameLoader', array('action' => $action));
	?>

</div>

</body>
</html>
