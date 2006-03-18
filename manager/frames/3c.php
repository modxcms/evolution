<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");


$theme = $manager_theme ? "$manager_theme/":"";

function constructLink($action, $img, $text, $allowed, $theme) {
	if($allowed==1) {
?>
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $theme; ?>contextMenu.css<?php echo "?$theme_refresher";?>" />
<div class="menuLink" onmouseover="this.className='menuLinkOver';" onmouseout="this.className='menuLink';" onclick="this.className='menuLink'; parent.menuHandler(<?php echo $action ; ?>); parent.hideMenu();">
	<img src='media/style/<?php echo $theme; ?>images/icons/<?php echo $img; ?>.gif' align="absmiddle" ><?php echo $text; ?>
</div>
<?php
	} else {
?>
<div class="menuLinkDisabled">
	<img src='media/style/<?php echo $theme; ?>images/icons/<?php echo $img; ?>.gif' align="absmiddle" /><?php echo $text; ?>
</div>
<?php
	}
}


?>
<html>
<head>
<title>ContextMenu</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>contextMenu.css<?php echo "?$theme_refresher";?>" />
</head>
<body onselectstart="return false;" onblur="parent.hideMenu();">
<div id="nameHolder"></div>
<?php
constructLink(1, "context_view", $_lang["view_document"], 1,$theme);
constructLink(2, "save", $_lang["edit_document"], $modx->hasPermission('edit_document'),$theme);
constructLink(5, "cancel", $_lang["move_document"], $modx->hasPermission('edit_document'),$theme);
// Ryan: Duplicate Document
constructLink(7, "copy", $_lang["duplicate_document"], $modx->hasPermission('new_document'),$theme);
//Raymond:Create Folder
constructLink(11, "folder", $_lang["create_folder_here"], $modx->hasPermission('new_document'),$theme);
constructLink(3, "newdoc", $_lang["create_document_here"], $modx->hasPermission('new_document'),$theme);
constructLink(6, "weblink", $_lang["create_weblink_here"], $modx->hasPermission('new_document'),$theme);
?>
<div class="seperator"></div>
<?php
constructLink(4, "delete", $_lang["delete_document"], $modx->hasPermission('delete_document'),$theme);
constructLink(8, "b092", $_lang["undelete_document"], $modx->hasPermission('delete_document'),$theme);
?>
<div class="seperator"></div>
<?php
constructLink(9, "date", $_lang["publish_document"], $modx->hasPermission('edit_document'),$theme);
constructLink(10, "date", $_lang["unpublish_document"], $modx->hasPermission('edit_document'),$theme);
?>
</body>
</html>