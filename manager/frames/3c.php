<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
function constructLink($action, $img, $text, $allowed) {
	if($allowed==1) {
?>
<div class="menuLink" onmouseover="this.className='menuLinkOver';" onmouseout="this.className='menuLink';" onclick="this.className='menuLink'; parent.menuHandler(<?php echo $action ; ?>); parent.hideMenu();">
	<img src='media/images/icons/<?php echo $img; ?>.gif' align=absmiddle><?php echo $text; ?>
</div>
<?php 
	} else {
?>
<div class="menuLinkDisabled">
	<img src='media/images/icons/<?php echo $img; ?>.gif' align=absmiddle><?php echo $text; ?>
</div>
<?php
	}
}


?>
<html>
<head>
<title>ContextMenu</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css<?php echo "?$theme_refresher";?>" />
</head>
<body onselectstart="return false;" onblur="parent.hideMenu();">
<div id="context_menu_holder">
<div id="nameHolder"></div>
<?php
constructLink(1, "context_view", $_lang["view_document"], 1);
constructLink(2, "save", $_lang["edit_document"], $modx->hasPermission('edit_document'));
constructLink(5, "cancel", $_lang["move_document"], $modx->hasPermission('edit_document'));
//Raymond:Create Folder
constructLink(11, "folder", $_lang["create_folder_here"], $modx->hasPermission('new_document'));
constructLink(3, "newdoc", $_lang["create_document_here"], $modx->hasPermission('new_document'));
constructLink(6, "weblink", $_lang["create_weblink_here"], $modx->hasPermission('new_document'));
?>
<div class="seperator"></div>
<?php
constructLink(4, "delete", $_lang["delete_document"], $modx->hasPermission('delete_document'));
constructLink(8, "b092", $_lang["undelete_document"], $modx->hasPermission('delete_document'));
?>
<div class="seperator"></div>
<?php
constructLink(9, "date", $_lang["publish_document"], $modx->hasPermission('edit_document'));
constructLink(10, "date", $_lang["unpublish_document"], $modx->hasPermission('edit_document'));
?>
</div>
</body>
</html>
