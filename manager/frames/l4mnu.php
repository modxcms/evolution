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
<!--[if IE]>
<style type="text/css" media="screen, tv, projection">
body { behavior: url(../assets/js/csshover.htc); }
#divNav{margin-top:3px;}
#divNav li ul li a{margin:4px 0 0 0;padding:4px 5px 2px 5px;font-weight:bold;}
</style>
<![endif]-->
<script language="JavaScript" type="text/javascript">
    function showWin() {
    	window.open('../');
    }
    startList = function() {
        if (document.all && document.getElementById) {
    		navRoot = document.getElementById("nav");
    		for (i=0; i<navRoot.childNodes.length; i++) {  
    			node = navRoot.childNodes[i];  
    			if (node.nodeName=="li") { 
    				node.onmouseover=function() {  
    					this.className+=" over";    
    				}  
    				node.onmouseout=function() {
    					this.className=this.className.replace (/ over/, '');   
    				}
    			}
    		} 
    	}
    }  

    function stopIt() {
    	top.scripter.stopWork();
    }
    window.onload=startList;
</script>
</head>
<body>
<form name="menuForm">
<div id="divNav">
<ul id="nav">

<!-- Settings -->
<?php if($modx->hasPermission('settings') || $modx->hasPermission('edit_parser') || $modx->hasPermission('logs') || $modx->hasPermission('file_manager')) { ?>		
    <li id="liAdmin"><li><a href="#"><?php echo $_lang["administration"]; ?></a>
        <ul>
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
            <?php 	if($modx->hasPermission('file_manager')) { ?>		
            <li><a onclick="this.blur();" href="index.php?a=31" target="main"><?php echo $_lang["manage_files"]; ?></a></li>
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
<!-- Users -->
<?php if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('access_permissions')||$modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user') || $modx->hasPermission('web_access_permissions')) { ?>		
    <li id="liUsers"><li><a href="#"><?php echo $_lang["users"]; ?></a>
        <ul>
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
<!-- Site -->
    <li id="liSite"><a href="#"><?php echo $_lang["site"]; ?></a>
        <ul>
			<li><a onclick="this.blur();" href="index.php?a=2" target="main"><?php echo $_lang["home"]; ?></a></li>
			<li><a onclick="this.blur();" href="javascript:showWin();"><?php echo $_lang["launch_site"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=26" target="main"><?php echo $_lang["refresh_site"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=70" target="main"><?php echo $_lang["site_schedule"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=68" target="main"><?php echo $_lang["visitor_stats"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=69" target="main"><?php echo $_lang["visitor_stats_online"]; ?></a></li>
        </ul>
    </li>			

<?php  if($modx->hasPermission('new_document')) { ?>
    <li id="liContent"><li><a href="#"><?php echo $_lang["content"]; ?></a>
        <ul>
			<li><a onclick="this.blur();" href="index.php?a=85" target="main"><?php echo $_lang["add_folder"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=4" target="main"><?php echo $_lang["add_document"]; ?></a></li>
			<li><a onclick="this.blur();" href="index.php?a=72" target="main"><?php echo $_lang["add_weblink"]; ?></a></li>
        </ul>
    </li>
<?php } ?>

<?php if($modx->hasPermission('messages') || $modx->hasPermission('change_password')) { ?>
    <li id="liAccount"><li><a href="#"><?php echo $_lang["my_account"]; ?></a>
        <ul>
            <?php if($modx->hasPermission('messages')) { ?>
            <li><a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?> <span id="msgCounter">(? / ? )</span></a></li>
            <?php } ?>
            <?php if($modx->hasPermission('change_password')) { ?>
            <li><a onclick="this.blur();" href="index.php?a=28" target="main"><?php echo $_lang["change_password"]; ?></a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>
<?php if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags') || $modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>		
    <li id="liResources"><li><a href="#"><?php echo $_lang["resources"]; ?></a>
        <ul>
            <?php if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module') || $modx->hasPermission('exec_module')) { ?>
            <li><a onclick="this.blur();" href="index.php?a=106" target="main"><?php echo $_lang["module_management"]; ?></a></li>
            <?php } ?>
            <?php if($hasAccess) { ?>					
            <li><a onclick="this.blur();" href="index.php?a=76" target="main"><?php echo $_lang["resource_management"]; ?></a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>
<?php if($modx->hasPermission('help')) { ?>		
    <li id="liHelp"><li><a href="#"><?php echo $_lang["help"]; ?></a>
        <ul>
            <li><a href="javascript:openCredits();"><?php echo $_lang["credits"]; ?></a></li>
            <li><a href="index.php?a=9" target="main"><?php echo $_lang["help"]; ?></a>	</li>			
            <li><a href="index.php?a=59" target="main"><?php echo $_lang["about"]; ?></a></li>
        </ul>
    </li>
<?php } ?>
    
    <li id="liLogout"><a onclick="this.blur();" href="index.php?a=8" target="_top"><?php echo $_lang["logout"]; ?></a></li>
        
</ul>
</div>
</form>
</body>
</html>