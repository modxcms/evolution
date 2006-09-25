<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

	// close the session as it is not used here
	// this should speed up frame loading, does it?
	// session_write_close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>nav</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />
<script src="media/script/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="media/script/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
    function showWin() {
    	window.open('../');
    }

    function stopIt() {
    	top.scripter.stopWork();
    }

    function openCredits() {
    	parent.main.document.location.href = "index.php?a=18";
    	xwwd = window.setTimeout('stopIt()', 2000);
    }

	function NavToggle(element) {
		// This gives the active tab its look
		var navid = document.getElementById('nav');
		var navs = navid.getElementsByTagName('li');
		var navsCount = navs.length;
		for(j = 0; j < navsCount; j++) {
			active = (navs[j].id == element.parentNode.id) ? "active" : "";
			navs[j].className = active;
		}

		// Don't use effect if Opera detected
		if(navigator.userAgent.toLowerCase().indexOf("opera")==-1){
			//Hide all content containers
			contents = document.getElementsByClassName('subnav');
			contentsCount = contents.length;
			for(var i = 0; i < contentsCount; i++) {
				contents[i].style.display = 'none';
			}

			//Extract content container id from href
			ele = element.getAttribute('href').replace(/^.*\#/,'');

			//Magic Happens
			new Effect.Appear(ele,{duration:0.15});
		}
	}
</script>
</head>
<body id="topMenu">
<form name="menuForm" action="l4mnu.php">
<div id="Navcontainer">
<div id="divNav">

<ul id="nav">
<!-- Site -->
<li id="limenu3" class="active"><a href="#menu3" onclick="new NavToggle(this); return false;"><?php echo $_lang["site"]; ?></a>
<ul class="subnav" id="menu3">
<!--home--><li><a onclick="this.blur();" href="index.php?a=2" target="main"><?php echo $_lang["home"]; ?></a></li>
<!--preview--><li><a onclick="this.blur();" href="../" target="_blank"><?php echo $_lang["launch_site"]; ?></a></li>
<!--new-document--><li><a onclick="this.blur();" href="index.php?a=4" target="main"><?php echo $_lang["add_document"]; ?></a></li>
<!--new-folder--><li><a onclick="this.blur();" href="index.php?a=85" target="main"><?php echo $_lang["add_folder"]; ?></a></li>
<!--new-weblink--><li><a onclick="this.blur();" href="index.php?a=72" target="main"><?php echo $_lang["add_weblink"]; ?></a></li>
<!--search--><li><a onclick="this.blur();" href="index.php?a=71" target="main"><?php echo $_lang['search']; ?></a></li>

</ul>
</li>

<!--Resources-->
<?php if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags') || $modx->hasPermission('file_manager')) { ?>
<li id="limenu5"><a onclick="new NavToggle(this);return false;" href="#menu5"><?php echo $_lang["resources"]; ?></a>
<ul class="subnav" id="menu5"><!-- break these out individually soon -->
<!--resources--><li><a onclick="this.blur();" href="index.php?a=76" target="main"><?php echo $_lang["resource_management"]; ?></a></li>
<?php 	if($modx->hasPermission('file_manager')) { ?>
<!--manage-files--><li><a onclick="this.blur();" href="index.php?a=31" target="main"><?php echo $_lang["manage_files"]; ?></a></li>
<?php } ?></ul>
</li>
<?php } ?>

<!-- Tools -->
<li id="limenu1-1"><a href="#menu1-1" onclick="new NavToggle(this); return false;"><?php echo $_lang["tools"]; ?></a>
<ul class="subnav" id="menu1-1">
<!--clear-cache--><li><a onclick="this.blur();" href="index.php?a=26" target="main"><?php echo $_lang["refresh_site"]; ?></a></li>
<?php if($modx->hasPermission('bk_manager')) { ?>
<!--backup-mgr--><li><a onclick="this.blur();" href="index.php?a=93" target="main"><?php echo $_lang["bk_manager"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('settings')) { ?>
<!--unlock-pages--><li><a onclick="this.blur();" href="javascript:top.scripter.removeLocks();"><?php echo $_lang["remove_locks"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('new_document')) { ?>
<!--import-html--><li><a onclick="this.blur();" href="index.php?a=95" target="main"><?php echo $_lang["import_site"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('edit_document')) { ?>
<!--export-static-site--><li><a onclick="this.blur();" href="index.php?a=83" target="main"><?php echo $_lang["export_site"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('settings')) { ?>
<!--configuration--><li><a onclick="this.blur();" href="index.php?a=17" target="main"><?php echo $_lang["edit_settings"]; ?></a></li>
<?php } ?>
</ul>
</li>

<!-- Modules -->
<?php  if($modx->hasPermission('exec_module')) { ?>
<li id="limenu9"><a href="#menu9" onclick="new NavToggle(this); return false;"><?php echo $_lang["modules"]; ?></a>
<ul class="subnav" id="menu9">
<?php if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module')) { ?>
<!--manage-modules--><li><a onclick="this.blur();" href="index.php?a=106" target="main"><?php echo $_lang["module_management"]; ?></a></li>
<?php } ?>
<?php
$list = '';  // initialize list variable
$rs = $modx->db->select('*',$modx->getFullTableName('site_modules'));  // get modules
while($content = $modx->db->getRow($rs)) {
$list .= '<li><a onclick="this.blur();" href="index.php?a=112&amp;id='.$content['id'].'" target="main">'.$content['name'].'</a></li>'."\n";
}
echo $list;
?>
</ul>
</li>
<?php } ?>


<!-- Reports -->
<li id="limenu1-2"><a href="#menu1-2" onclick="new NavToggle(this); return false;"><?php echo $_lang["reports"]; ?></a>
<ul class="subnav" id="menu1-2">
<!--site-sched--><li><a onclick="this.blur();" href="index.php?a=70" target="main"><?php echo $_lang["site_schedule"]; ?></a></li>
<?php if($modx->hasPermission('view_eventlog')) { ?>
<!--manager-events--><li><a onclick="this.blur();" href="index.php?a=114" target="main"><?php echo $_lang["eventlog_viewer"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('logs')) { ?>
<!--manager-audit-trail--><li><a onclick="this.blur();" href="index.php?a=13" target="main"><?php echo $_lang["view_logging"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('settings')) { ?>
<!--system-info--><li><a onclick="this.blur();" href="index.php?a=53" target="main"><?php echo $_lang["view_sysinfo"]; ?></a></li>
<?php } ?>
</ul>
</li>


<!-- Security (users) -->
<?php if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('access_permissions')||$modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user') || $modx->hasPermission('web_access_permissions')) { ?>
<li id="limenu2"><a href="#menu2" onclick="new NavToggle(this); return false;"><?php echo $_lang["users"]; ?></a>
<ul class="subnav" id="menu2">
<?php if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) { ?>
<!--manager-users--><li><a onclick="this.blur();" href="index.php?a=75" target="main"><?php echo $_lang["user_management_title"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) { ?>
<!--web-users--><li><a onclick="this.blur();" href="index.php?a=99" target="main"><?php echo $_lang["web_user_management_title"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('new_role')||$modx->hasPermission('edit_user')) { ?>
<!--roles--><li><a onclick="this.blur();" href="index.php?a=86" target="main"><?php echo $_lang["role_management_title"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('access_permissions')) { ?>
<!--manager-perms--><li><a onclick="this.blur();" href="index.php?a=40" target="main"><?php echo $_lang["manager_permissions"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('web_access_permissions')) { ?>
<!--web-user-perms--><li><a onclick="this.blur();" href="index.php?a=91" target="main"><?php echo $_lang["web_permissions"]; ?></a></li>
<?php } ?>
</ul>
</li>
<?php } ?>

</ul>

</div></div>
</form>
</body>
</html>