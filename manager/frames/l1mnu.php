<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

	// close the session as it is not used here
	// this should speed up frame loading, does it?
	session_write_close();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>nav</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>navbar.css<?php echo "?$theme_refresher";?>" />
<script language="JavaScript" type="text/javascript">
function showWin() {
	window.open('../');
}


function openCredits() {
	parent.main.document.location.href = "index.php?a=18";
	xwwd = window.setTimeout('stopIt()', 2000);
}


function stopIt() {
	top.scripter.stopWork();
}

// Scroller added by Raymond
var timer = 0,speed = 0;
function scrollup(){
	var navbar = document.getElementById ? document.getElementById("navbar"):document.all["navbar"];
	var navbarc = document.getElementById ? document.getElementById("navbarcontent"):document.all["navbarcontent"];
	var navbarheight= parseInt(navbar.style.height);
	var navbaractualheight= parseInt(navbarc.offsetHeight);
	var nctop = parseInt(navbarc.style.top||0);
	speed = (timer) ? speed+1:4;
	if (nctop>(navbarheight-navbaractualheight)) navbarc.style.top=(nctop-speed)+"px";
	timer=setTimeout("scrollup()",60)
}
function scrollDown(){
	var navbar = document.getElementById ? document.getElementById("navbar"):document.all["navbar"];
	var navbarc = document.getElementById ? document.getElementById("navbarcontent"):document.all["navbarcontent"];
	var navbarheight= parseInt(navbar.style.height);
	var navbaractualheight= parseInt(navbarc.offsetHeight);
	var nctop = parseInt(navbarc.style.top||0);
	speed = (timer) ? speed+1:4;
	if (nctop<0) navbarc.style.top=(nctop+speed)+"px"
	timer=setTimeout("scrollDown()",60)
};
function scrollReset(){
	clearTimeout(timer);
	timer = 0;
};
function scrollerResize(e){
	var navbar = document.getElementById ? document.getElementById("navbar"):document.all["navbar"];
	var h = window.innerHeight ? window.innerHeight:parseInt(document.body.offsetHeight);
	if (h>40) navbar.style.height = (h-(window.innerHeight ? 48:50))+"px"; // weird!
};

</script>
</head>
<body onresize = "scrollerResize()" onload="scrollerResize()">

<!--start container -->
<div id="container">
<form name="menuForm">
<img src="media/images/_tx_.gif" width="1" height="20" alt="spacer" />
<!--start scroller -->
<div style="position:relative;width:100%;">
<div id="up" class="scrollbtn" style="position:relative;" title="<?php echo $_lang['scroll_up'];?>" onmousedown="scrollDown()" onmouseup="scrollReset()" onmouseout="scrollReset()"><img src="media/images/icons/arrow_up.gif" width="5" height="6" alt="up" style="margin-top:3px" /></div>
<div id="navbar" style="position:relative;overflow:hidden;height:280px">
<div id="navbarcontent" style="position:relative;">

<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="5%"></td>
		<td width="44%" valign="top">
			<div class="menuHeader"><?php echo $_lang["site"]; ?></div>
			<a onclick="this.blur();" href="index.php?a=2" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["home"]; ?></a>
			<a onclick="this.blur();" href="javascript:showWin();"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["launch_site"]; ?></a>
			<a onclick="this.blur();" href="index.php?a=26" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["refresh_site"]; ?></a>
			<a onclick="this.blur();" href="index.php?a=70" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["site_schedule"]; ?></a>
			<a onclick="this.blur();" href="index.php?a=68" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["visitor_stats"]; ?></a>
			<a onclick="this.blur();" href="index.php?a=69" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["visitor_stats_online"]; ?></a>			
<?php  if($modx->hasPermission('new_document')) { ?>
			<br />
			<div class="menuHeader"><?php echo $_lang["content"]; ?></div>
			<a onclick="this.blur();" href="index.php?a=85" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["add_folder"]; ?></a>
			<a onclick="this.blur();" href="index.php?a=4" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["add_document"]; ?></a>
			<a onclick="this.blur();" href="index.php?a=72" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["add_weblink"]; ?></a>
<?php } ?>
<?php if($modx->hasPermission('messages') || !$modx->hasPermission('change_password')) { ?>
			<br />
			<div class="menuHeader"><?php echo $_lang["my_account"]; ?></div>
<?php 	if($modx->hasPermission('messages')) { ?>
				<a onclick="this.blur();" href="index.php?a=10" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["messages"]; ?> <span id="msgCounter">(? / ? )</span></a>
<?php 	} 
		if($modx->hasPermission('change_password')) { ?>
				<a onclick="this.blur();" href="index.php?a=28" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["change_password"]; ?></a>
<?php 	} ?>
<?php } ?>
<?php 
	  $hasAccess = $modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('access_permissions')||$modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user') || $modx->hasPermission('web_access_permissions');
	  if($hasAccess) { ?>		
			<br />
			<div class="menuHeader"><?php echo $_lang["users"]; ?></div>
<?php 	if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) { ?>					
				<a onclick="this.blur();" href="index.php?a=75" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["user_management_title"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) { ?>		
				<a onclick="this.blur();" href="index.php?a=99" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["web_user_management_title"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('new_role')||$modx->hasPermission('edit_user')) { ?>					
				<a onclick="this.blur();" href="index.php?a=86" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["role_management_title"]; ?></a>
<?php 	} ?>
<?php if($modx->hasPermission('access_permissions')) { ?>					
				<a onclick="this.blur();" href="index.php?a=40" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["manager_permissions"]; ?></a>
<?php 	} ?>
<?php if($modx->hasPermission('web_access_permissions')) { ?>					
				<a onclick="this.blur();" href="index.php?a=91" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["web_permissions"]; ?></a>
<?php 	} ?>
<?php } ?>

		</td>
		<td width="2%">&nbsp;</td>
		<td width="44%" valign="top">
<?php 
	  $hasAccess = $modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags');
	  if($hasAccess || $modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>		
			<div class="menuHeader"><?php echo $_lang["resources"]; ?></div>
<?php 	if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>					
				<a onclick="this.blur();" href="index.php?a=106" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["module_management"]; ?></a>
<?php 	} ?>
<?php 	if($hasAccess) { ?>					
				<a onclick="this.blur();" href="index.php?a=76" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["resource_management"]; ?></a>
<?php 	} ?>
<?php } ?>
<?php if($modx->hasPermission('settings') || $modx->hasPermission('edit_parser') || $modx->hasPermission('logs') || $modx->hasPermission('file_manager')) { ?>		
			<br />
			<div class="menuHeader"><?php echo $_lang["administration"]; ?></div>
<?php 	if($modx->hasPermission('settings')) { ?>		
				<a onclick="this.blur();" href="index.php?a=17" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["edit_settings"]; ?></a>
				<a onclick="this.blur();" href="index.php?a=53" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["view_sysinfo"]; ?></a>
				<a onclick="this.blur();" href="javascript:top.scripter.removeLocks();"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["remove_locks"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('view_eventlog')) { ?>		
				<a onclick="this.blur();" href="index.php?a=114" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["eventlog_viewer"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('logs')) { ?>		
				<a onclick="this.blur();" href="index.php?a=13" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["view_logging"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('file_manager')) { ?>		
				<a onclick="this.blur();" href="index.php?a=31" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["manage_files"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('bk_manager')) { ?>		
				<a onclick="this.blur();" href="index.php?a=93" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["bk_manager"]; ?></a>
<?php 	} ?>
<?php } ?>
<?php if($modx->hasPermission('edit_document')) { ?>
			<a onclick="this.blur();" href="index.php?a=83" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["export_site"]; ?></a>			
<?php } ?>
<?php if($modx->hasPermission('new_document')) { ?>
			<a onclick="this.blur();" href="index.php?a=95" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["import_site"]; ?></a>
<?php } ?>
<?php if($modx->hasPermission('help')) { ?>		
			<br />
			<div class="menuHeader"><?php echo $_lang["help"]; ?></div>
				<a onclick="this.blur();" href="javascript:openCredits();"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["credits"]; ?></a>
				<a onclick="this.blur();" href="index.php?a=9" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["help"]; ?></a>				
				<a onclick="this.blur();" href="index.php?a=59" target="main"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["about"]; ?></a>
<?php } ?>
<br /><a onclick="this.blur();" href="index.php?a=8" target="_top"><img src='media/images/misc/arrow.gif' alt="Arrow!" /><?php echo $_lang["logout"]; ?></a>
		</td>
		<td width="5%">&nbsp;</td>
	</tr>

</table>

</div>
</div>
<div id="dn" class="scrollbtn" style="position:relative;" title="<?php echo $_lang['scroll_dn'];?>" onmousedown="scrollup()" onmouseup="scrollReset()" onmouseout="scrollReset()"><img src="media/images/icons/arrow_dn.gif" width="5" height="6" alt="down" style="margin-top:3px" /></div>
</div>
<!-- end scroller -->

</form>

</div><!-- end #container -->



</body>
</html>