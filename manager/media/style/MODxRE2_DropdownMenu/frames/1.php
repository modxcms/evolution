<?php

if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
header("X-XSS-Protection: 0");

$_SESSION['browser'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 1') !== false) ? 'legacy_IE' : 'modern';

$modx->invokeEvent('OnManagerPreFrameLoader', array('action' => $action));

$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

if(!isset($modx->config['manager_menu_height'])) {
	$modx->config['manager_menu_height'] = 48;
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
<html <?php echo (isset($modx_textdir) && $modx_textdir ? 'dir="rtl" lang="' : 'lang="') . $mxla . '" xml:lang="' . $mxla . '"'; ?> style="height:100%">
<head style="height:100%">
	<title><?php echo $site_name ?>- (MODX CMS Manager)</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset ?>" />
	<link rel="stylesheet" type="text/css" href="media/style/common/font-awesome/css/font-awesome.min.css" />
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
			config: {
				mail_check_timeperiod: "<?php echo $modx->config['mail_check_timeperiod'] ?>",
				menu_height: "<?php echo $menu_height ?>",
				tree_width: "<?php echo $tree_width ?>",
				tree_min_width: "<?php echo $tree_min_width ?>",
				site_start: "<?php echo $modx->config['site_start']?>",
				tree_page_click: "<?php echo(!empty($modx->config['tree_page_click']) ? $modx->config['tree_page_click'] : '27'); ?>",
				modal: 'notEVOModal',
				theme: "<?php echo $modx->config['manager_theme'] ?>",
				which_browser: "<?php echo $user['which_browser']; ?>",
				layout: "<?php echo $manager_layout ?>",
				textdir: "<?php echo $modx_textdir ?>",
			},
			lang: {
				already_deleted: "<?php echo $_lang['already_deleted']; ?>",
				collapse_tree: "<?php echo $_lang['collapse_tree']; ?>",
				confirm_delete_resource: "<?php echo $_lang['confirm_delete_resource']; ?>",
				confirm_empty_trash: "<?php echo $_lang['confirm_empty_trash']; ?>",
				confirm_publish: "<?php echo $_lang['confirm_publish']; ?>",
				confirm_remove_locks: "<?php echo $_lang['confirm_remove_locks'] ?>",
				confirm_resource_duplicate: "<?php echo $_lang['confirm_resource_duplicate'] ?>",
				confirm_undelete: "<?php echo $_lang['confirm_undelete']; ?>",
				confirm_unpublish: "<?php echo $_lang['confirm_unpublish']; ?>",
				empty_recycle_bin: "<?php echo $_lang['empty_recycle_bin']; ?>",
				empty_recycle_bin_empty: "<?php echo addslashes($_lang['empty_recycle_bin_empty']); ?>",
				expand_tree: "<?php echo $_lang['expand_tree']; ?>",
				inbox: "<?php echo $_lang['inbox']; ?>",
				loading_doc_tree: "<?php echo $_lang['loading_doc_tree'] ?>",
				loading_menu: "<?php echo $_lang['loading_menu'] ?>",
				not_deleted: "<?php echo $_lang['not_deleted']; ?>",
				unable_set_link: "<?php echo $_lang['unable_set_link']; ?>",
				unable_set_parent: "<?php echo $_lang['unable_set_parent']; ?>",
				working: "<?php echo $_lang['working'] ?>"
			},
			style: {
				collapse_tree: "<?php echo addslashes($_style['collapse_tree']) ?>",
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
				tree_plusnode: "<?php echo addslashes($_style['tree_plusnode']) ?>"
			},
			permission: {
				assets_images: "<?php echo $modx->hasPermission('assets_images') ? 1 : 0; ?>",
				delete_document: "<?php echo $modx->hasPermission('delete_document') ? 1 : 0; ?>",
				edit_chunk: "<?php echo $modx->hasPermission('edit_chunk') ? 1 : 0; ?>",
				edit_plugin: "<?php echo $modx->hasPermission('edit_plugin') ? 1 : 0; ?>",
				edit_snippet: "<?php echo $modx->hasPermission('edit_snippet') ? 1 : 0; ?>",
				edit_template: "<?php echo $modx->hasPermission('edit_template') ? 1 : 0; ?>",
				new_document: "<?php echo $modx->hasPermission('new_document') ? 1 : 0; ?>",
				publish_document: "<?php echo $modx->hasPermission('publish_document') ? 1 : 0; ?>"
			},
			openedArray: [],
			lockedElementsTranslation: <?php echo json_encode($unlockTranslations) . "\n" ?>
		};
		<?php
		$opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
		echo (empty($opened) ? '' : 'modx.openedArray[' . implode("] = 1;\n		modx.openedArray[", $opened) . '] = 1;') . "\n";
		?>
	</script>
	<script src="media/style/<?php echo $modx->config['manager_theme']; ?>/modx.js"></script>
</head>
<body>
<div id="frameset" class="<?php echo $body_class ?>">
	<div id="mainMenu">
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
				<li>
					<a href="../" target="_blank" title="<?php echo $_lang['preview'] ?>" onclick="setLastClickedElement(0,0);this.blur();">
						<i class="fa fa-desktop"></i>
					</a>
				</li>
				<?php if($modx->hasPermission('settings') || $modx->hasPermission('view_eventlog') || $modx->hasPermission('logs') || $modx->hasPermission('help') ) { ?>
				<li class="dropdown">
					<a class="dropdown-toggle" onclick="modx.mainMenu.navToggle(this); return false;"><i class="fa fa-sliders fa-2x"></i></a>
					<ul class="dropdown-menu">
						<?php if($modx->hasPermission('settings')) { ?>
							<li>
								<a href="index.php?a=17" target="main" onclick="setLastClickedElement(0,0);this.blur();">
									<i class="fa fa-cog fw"></i><?php echo $_lang['edit_settings'] ?>
								</a>
							</li>
						<?php } ?>
						<?php if($modx->hasPermission('view_eventlog')) { ?>
							<li>
								<a href="index.php?a=70" target="main" onclick="setLastClickedElement(0,0);this.blur();">
									<i class="fa fa-calendar"></i><?php echo $_lang['site_schedule'] ?>
								</a>
							</li>
						<?php } ?>
						<?php if($modx->hasPermission('view_eventlog')) { ?>
							<li>
								<a href="index.php?a=114" target="main" onclick="setLastClickedElement(0,0);this.blur();">
									<i class="fa fa-exclamation-triangle"></i><?php echo $_lang['eventlog_viewer'] ?>
								</a>
							</li>
						<?php } ?>
						<?php if($modx->hasPermission('logs')) { ?>
							<li>
								<a href="index.php?a=13" target="main" onclick="setLastClickedElement(0,0);this.blur();">
									<i class="fa fa-user-secret"></i><?php echo $_lang['view_logging'] ?>
								</a>
							</li>
							<li>
								<a href="index.php?a=53" target="main" onclick="setLastClickedElement(0,0);this.blur();">
									<i class="fa fa-info-circle"></i><?php echo $_lang['view_sysinfo'] ?>
								</a>
							</li>
						<?php } ?>
						<?php if($modx->hasPermission('help')) { ?>
							<li>
								<a href="index.php?a=9#version_notices" target="main" onclick="setLastClickedElement(0,0);this.blur();">
									<i class="fa fa-question-circle"></i><?php echo $_lang['help'] ?>
								</a>
							</li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
				<li class="dropdown account">
					<a class="dropdown-toggle" onclick="modx.mainMenu.navToggle(this); return false;">
						<div class="username"><?php echo $user['username'] ?></div>
						<?php if($user['photo']) { ?>
							<div class="icon photo" style="background-image: url(<?php echo MODX_SITE_URL . $user['photo'] ?>);"></div>
						<?php } else { ?>
							<div class="icon"><i class="fa fa-user-circle fa-2x"></i></div>
						<?php } ?>
						<div id="msgCounter"></div>
					</a>
					<ul class="dropdown-menu">
						<li id="newMail"></li>
						<?php if($modx->hasPermission('change_password')) { ?>
							<li>
								<a onclick="this.blur();" href="index.php?a=28" target="main">
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
						echo sprintf('<li><span title="%s &ndash; %s" %s>' . $version . ' %s</span></li>', $site_name, $modx->getVersionData('full_appname'), $style, $modx->config['settings_version']);
						?>
					</ul>
				</li>
			</ul>
		</div>
		<div class="col float-right">
			<div id="searchform">
				<form action="index.php?a=71#results" method="post" target="main">
					<input type="hidden" value="Search" name="submitok" />
					<label for="searchid">
						<i class="fa fa-search fa-2x"></i>
					</label>
					<input type="text" id="searchid" name="searchid" size="25" class="form-control input-sm">
					<div class="mask"></div>
				</form>
			</div>
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
	<?php
	$modx->invokeEvent('OnManagerFrameLoader', array('action' => $action));
	?>
</div>
</body>
</html>
