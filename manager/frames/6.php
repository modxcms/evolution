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
<style>
BODY {
	background-image: 				url("media/images/bg/context.gif");
	background-color: 				#fff;
	background-position: 			top left;
	background-repeat: 				repeat-y;
	margin:							0px;
	padding:						2px;
	border: 						1px solid #003399;
	border-left-color:	ButtonHighlight;
	border-right-color:	ButtonShadow;
	border-top-color:	ButtonHighlight;
	border-bottom-color:ButtonShadow;
	overflow:						hidden;
}

.menuLink {
	cursor:							pointer;
	font:							menu;
	color:							MenuText;

	padding: 						3px 16px 3px 2px;
}

.menuLinkOver {
	cursor:							pointer;
	background-position: 			bottom left;
	background-repeat: 				repeat-x;	
	background:						white;
	background-image: 				url("media/images/misc/buttonbar_gs.gif");
	font:							menu;

	padding: 						2px 15px 2px 1px;
	border: 						1px solid #003399;
	border-left-color:	ButtonHighlight;
	border-right-color:	ButtonShadow;
	border-top-color:	ButtonHighlight;
	border-bottom-color:ButtonShadow;	
}

.menuLinkDisabled {
	cursor:							default;
	font: 							menu;

	padding: 						3px 16px 3px 2px;
	color:							graytext;
}

.menuLink IMG, .menuLinkOver IMG, .menuLinkDisabled IMG {
	margin-right:					8px;
}

.menuLink IMG, .menuLinkDisabled IMG {
	filter:							gray();
}

.menuLinkOver IMG {

}

#nameHolder {
	text-align:						right;
	cursor:							default;
	font-family: 					Tahoma, Helvetica, sans-serif;
	font-weight:					bold;
	font-size:						11px;
	padding: 						2px 2px 2px 2px;
	margin-bottom:					2px;
	border: 						1px solid #003399;
	border-left-color:	ButtonHighlight;
	border-right-color:	ButtonShadow;
	border-top-color:	ButtonHighlight;
	border-bottom-color:ButtonShadow;	
	background-position: 			bottom left;
	background-repeat: 				repeat-x;	
	background:						white;
	background-image: 				url("media/images/misc/buttonbar.gif");
}

.seperator {
	font-size:      				0pt;
	height:         				1px;
	background-color: 				#6A8CCB;
	overflow:       				hidden;
	margin:							3px 1px 3px 28px;
}
					
</style>
</head>
<body onselectstart="return false;" onblur="parent.hideMenu();">
<div id='nameHolder'></div>
<?php
constructLink(1, "context_view", $_lang["view_document"], 1);
constructLink(2, "save", $_lang["edit_document"], $_SESSION['permissions']['edit_document']);
constructLink(5, "cancel", $_lang["move_document"], $_SESSION['permissions']['edit_document']);
//Raymond:Create Folder
constructLink(11, "folder", $_lang["create_folder_here"], $_SESSION['permissions']['new_document']);
constructLink(3, "newdoc", $_lang["create_document_here"], $_SESSION['permissions']['new_document']);
constructLink(6, "weblink", $_lang["create_weblink_here"], $_SESSION['permissions']['new_document']);
?>
<div class="seperator"></div>
<?php
constructLink(4, "delete", $_lang["delete_document"], $_SESSION['permissions']['delete_document']);
constructLink(8, "b092", $_lang["undelete_document"], $_SESSION['permissions']['delete_document']);
?>
<div class="seperator"></div>
<?php
constructLink(9, "date", $_lang["publish_document"], $_SESSION['permissions']['edit_document']);
constructLink(10, "date", $_lang["unpublish_document"], $_SESSION['permissions']['edit_document']);
?>
</body>
</html>
