<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
$theme = $manager_theme ? "$manager_theme/":"";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>nav</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>l3navbar.css<?php echo "?$theme_refresher";?>" />
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
</script>

<style>
body {
	margin : 0px 0px 0px 0px;
	background: #fff	url("media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/bg/treeback.jpg");
	background-position: top right;
}
</style>
</head>

<body>
<div id="container" >
<form name="menuForm">
<div style="height:15px;width:100%" ><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" class="buttonoc"><a href="javascript:parent.resizeFrame('0,20,17,*')"><b>Open/Close Menu</b></a></td></tr></table></div>
<div>
<table border="0" cellpadding="0" cellspacing="0" class="tdnormaltext">
    <tr>
    <td class="menuHeader"><?php echo $_lang["site"]; ?></td>
   <?php  if($modx->hasPermission('new_document')) { ?>
    <td class="menuHeader"><?php echo $_lang["content"]; ?></td>
	<?php } ?>

	<?php if($modx->hasPermission('messages') || $modx->hasPermission('change_password')) { ?>
	<td class="menuHeader"><?php echo $_lang["my_account"]; ?></td>
    <?php } ?>

	<?php if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user')) { ?>
	<td class="menuHeader"><?php echo $_lang["users"]; ?></td>
    <?php } ?>

	<?php
	  $hasAccess = $modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags');
	  if($hasAccess || $modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>
	<td class="menuHeader"><?php echo $_lang["resources"]; ?></td>
	<?php } ?>

<?php if($modx->hasPermission('settings') || $modx->hasPermission('edit_parser') || $modx->hasPermission('logs') || $modx->hasPermission('file_manager') || $modx->hasPermission('view_eventlog')) { ?>
    <td class="menuHeader"><?php echo $_lang["administration"]; ?></td>
	<?php } ?>

	<?php if($modx->hasPermission('help')) { ?>
    <td class="menuHeader"><?php echo $_lang["help"]; ?></td>
		<?php } ?>

  </tr>
  <tr>
    <td valign="top">
	<a href="index.php?a=2" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["home"]; ?></a>
	<a href="javascript:showWin();"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["launch_site"]; ?></a>
	<a href="index.php?a=26" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["refresh_site"]; ?></a>
	<a href="index.php?a=70" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["site_schedule"]; ?></a>
	<a href="index.php?a=68" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["visitor_stats"]; ?></a>
	<a href="index.php?a=69" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["visitor_stats_online"]; ?></a>

	</td>
<?php  if($modx->hasPermission('new_document')) { ?>
	<td valign="top">
	<a href="index.php?a=85" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["add_folder"]; ?></a>
	<a href="index.php?a=4" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["add_document"]; ?></a>
	<a href="index.php?a=72" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["add_weblink"]; ?></a>
	</td>
<?php } ?>
<?php if($modx->hasPermission('messages') || $modx->hasPermission('change_password')) { ?>
	<td valign="top">

<?php 	if($modx->hasPermission('messages')) { ?>
	<a href="index.php?a=10" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["messages"]; ?> <span id="msgCounter">(? / ? )</span></a>
<?php 	}
		if($modx->hasPermission('change_password')) { ?>
	<a href="index.php?a=28" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["change_password"]; ?></a>
<?php 	} ?>
	</td>
 <?php } ?>

<?php if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('access_permissions') || $modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user') || $modx->hasPermission('web_access_permissions')) { ?>
	<td valign="top">
<?php 	if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) { ?>
	<a href="index.php?a=75" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["user_management_title"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) { ?>
	<a href="index.php?a=99" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["web_user_management_title"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('new_role')||$modx->hasPermission('edit_user')) { ?>
				<a href="index.php?a=86" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["role_management_title"]; ?></a>
<?php 	} ?>
<?php if($modx->hasPermission('access_permissions')) { ?>
	<a href="index.php?a=40" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["manager_permissions"]; ?></a>
<?php 	} ?>
<?php if($modx->hasPermission('web_access_permissions')) { ?>
	<a href="index.php?a=91" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["web_permissions"]; ?></a>
<?php 	} ?>
	</td>
 <?php } ?>



<?php 	if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>
	<td valign="top">
				<a href="index.php?a=106" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!" /><?php echo $_lang["module_management"]; ?></a>
<?php 	} ?>
<?php 	if($hasAccess) { ?>
				<a href="index.php?a=76" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["resource_management"]; ?></a>
	</td>
<?php 	} ?>



<?php if($modx->hasPermission('settings') || $modx->hasPermission('edit_parser') || $modx->hasPermission('logs') || $modx->hasPermission('file_manager') || $modx->hasPermission('view_eventlog')) { ?>
	<td valign="top">

<?php 	if($modx->hasPermission('settings')) { ?>
	<a href="index.php?a=17" target="main"><img src='media/<?php echo $_lang["edit_settings"]; ?></a>

	<a href="index.php?a=53" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["view_sysinfo"]; ?></a>
	<a href="javascript:top.scripter.removeLocks();"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["remove_locks"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('view_eventlog')) { ?>
				<a href="index.php?a=114" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["eventlog_viewer"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('logs')) { ?>
	<a href="index.php?a=13" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["view_logging"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('file_manager')) { ?>
	<a href="index.php?a=31" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["manage_files"]; ?></a>
<?php 	} ?>
<?php 	if($modx->hasPermission('bk_manager')) { ?>
	<a href="index.php?a=93" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["bk_manager"]; ?></a>
<?php 	} ?>
<?php   if($modx->hasPermission('edit_document')) { ?>
		<a href="index.php?a=83" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["export_site"]; ?></a>
<?php   } ?>
<?php  if($modx->hasPermission('new_document')) { ?>
		<a href="index.php?a=95" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["import_site"]; ?></a>
<?php  } ?>
	</td>
<?php } ?>
    <td valign="top">
<?php if($modx->hasPermission('help')) { ?>
		<a href="javascript:openCredits();"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["credits"]; ?></a>
		<a href="index.php?a=9" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["help"]; ?></a>
		<a href="index.php?a=59" target="main"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["about"]; ?></a>
<?php } ?>
		<a href="index.php?a=8" target="_top"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/menu_dot.gif' alt="dot!"/><?php echo $_lang["logout"]; ?></a>
	</td>
  </tr>
</table>
</div>
</form>
</div>

</body>
</html>
