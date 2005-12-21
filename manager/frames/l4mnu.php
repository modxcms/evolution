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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>" />
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>l4navbar.css<?php echo "?$theme_refresher";?>" />
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
			new Effect.Appear(ele,{duration:0.2});
		}
	}
</script>
</head>
<body>
<form name="menuForm">
<div id="divNav">
<ul id="nav">
<!-- Site -->
    <li id="limenu3" class="active"><a href="#menu3" onclick="new NavToggle(this); return false;"><?php echo $_lang["site"]; ?></a>
        <ul class="subnav" id="menu3">
			<li><a onclick="this.blur();" href="index.php?a=2" target="main"><?php echo $_lang["home"]; ?></a></li>
			<li><a onclick="this.blur();" href="javascript:showWin();"><?php echo $_lang["launch_site"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=26" target="main"><?php echo $_lang["refresh_site"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=70" target="main"><?php echo $_lang["site_schedule"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=68" target="main"><?php echo $_lang["visitor_stats"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=69" target="main"><?php echo $_lang["visitor_stats_online"]; ?></a></li>
        </ul>
    </li>	

<?php  if($modx->hasPermission('new_document')) { ?>
<!-- Content -->
    <li id="limenu4"><a href="#menu4" onclick="new NavToggle(this); return false;"><?php echo $_lang["content"]; ?></a>
        <ul class="subnav" id="menu4">
			<li><a onclick="this.blur();" href="index.php?a=85" target="main"><?php echo $_lang["add_folder"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=4" target="main"><?php echo $_lang["add_document"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=72" target="main"><?php echo $_lang["add_weblink"]; ?></a></li>
        </ul>
    </li>
<?php } ?>

<?php if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags') || $modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module') || $modx->hasPermission('file_manager')) { ?>
<!-- Resources -->
    <li id="limenu6"><a href="#menu6" onclick="new NavToggle(this); return false;"><?php echo $_lang["resources"]; ?></a>
        <ul class="subnav" id="menu6">
            <?php if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=106" target="main"><?php echo $_lang["module_management"]; ?></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=76" target="main"><?php echo $_lang["resource_management"]; ?></a></li>
            <?php } ?>
            <?php 	if($modx->hasPermission('file_manager')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=31" target="main"><?php echo $_lang["manage_files"]; ?></a></li>
            <?php } ?>
            
        </ul>
    </li>
<?php } ?>

<?php if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('access_permissions')||$modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user') || $modx->hasPermission('web_access_permissions')) { ?>
<!-- Users -->
    <li id="limenu2"><a href="#menu2" onclick="new NavToggle(this); return false;"><?php echo $_lang["users"]; ?></a>
        <ul class="subnav" id="menu2">
            <?php if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=75" target="main"><?php echo $_lang["user_management_title"]; ?></a></li>
            <?php } ?>
            <?php 	if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=99" target="main"><?php echo $_lang["web_user_management_title"]; ?></a></li>
            <?php } ?>
            <?php 	if($modx->hasPermission('new_role')||$modx->hasPermission('edit_user')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=86" target="main"><?php echo $_lang["role_management_title"]; ?></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('access_permissions')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=40" target="main"><?php echo $_lang["manager_permissions"]; ?></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('web_access_permissions')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=91" target="main"><?php echo $_lang["web_permissions"]; ?></a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>

<?php if($modx->hasPermission('settings') || $modx->hasPermission('edit_parser') || $modx->hasPermission('logs')) { ?>
<!-- Settings -->
    <li id="limenu1"><a href="#menu1" onclick="new NavToggle(this); return false;"><?php echo $_lang["administration"]; ?></a>
        <ul class="subnav" id="menu1">
            <?php 	if($modx->hasPermission('settings')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=17" target="main"><?php echo $_lang["edit_settings"]; ?></a></li>
                <li><a onclick="this.blur();" href="index.php?a=53" target="main"><?php echo $_lang["view_sysinfo"]; ?></a></li>
                <li><a onclick="this.blur();" href="javascript:top.scripter.removeLocks();"><?php echo $_lang["remove_locks"]; ?></a></li>
            <?php } ?>
            <?php 	if($modx->hasPermission('view_eventlog')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=114" target="main"><?php echo $_lang["eventlog_viewer"]; ?></a></li>
            <?php } ?>
            <?php 	if($modx->hasPermission('logs')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=13" target="main"><?php echo $_lang["view_logging"]; ?></a></li>
            <?php } ?>
            <?php 	if($modx->hasPermission('bk_manager')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=93" target="main"><?php echo $_lang["bk_manager"]; ?></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('edit_document')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=83" target="main"><?php echo $_lang["export_site"]; ?></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('new_document')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=95" target="main"><?php echo $_lang["import_site"]; ?></a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>

<?php if($modx->hasPermission('messages') || $modx->hasPermission('change_password')) { ?>
<!-- Account -->
    <li id="limenu5"><a href="#menu5" onclick="new NavToggle(this); return false;"><?php echo $_lang["my_account"]; ?></a>
        <ul class="subnav" id="menu5">
            <?php if($modx->hasPermission('messages')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?> <span id="msgCounter">(? / ? )</span></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('change_password')) { ?>
                <li><a onclick="this.blur();" href="index.php?a=28" target="main"><?php echo $_lang["change_password"]; ?></a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>

<?php if($modx->hasPermission('help')) { ?>		
<!-- Help -->
    <li id="limenu7"><a href="#menu7" onclick="new NavToggle(this); return false;"><?php echo $_lang["help"]; ?></a>
        <ul class="subnav" id="menu7">
            <li><a href="javascript:openCredits();"><?php echo $_lang["credits"]; ?></a></li>
            <li><a href="index.php?a=9" target="main"><?php echo $_lang["help"]; ?></a></li>
            <li><a href="index.php?a=59" target="main"><?php echo $_lang["about"]; ?></a></li>
        </ul>
    </li>
<?php } ?>

<!-- Logout -->
    <li id="limenu8"><a onclick="this.blur();" href="index.php?a=8" target="_top"><?php echo $_lang["logout"]; ?></a></li>

</ul>
</div>
</form>
</body>
</html>