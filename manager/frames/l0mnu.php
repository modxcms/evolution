<?php if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly."); ?>
<html>
<head>
<title>Fancy WebFx navigation menu for IE</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset;?>" />

<link type="text/css" rel="StyleSheet" href="media/script/iemenu/officexp/officexp.css" />
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>coolButtons2.css<?php echo "?$theme_refresher";?>" />
<script type="text/javascript" language="JavaScript" src="media/script/ieemu.js"></script>
<script type="text/javascript" language="JavaScript" src="media/script/bin/webelm.js"></script>
<script type="text/javascript" language="JavaScript" src="media/script/cb2.js"></script>
<script language="JavaScript">
	document.setIncludePath("media/script/bin/");

	function document_oninit(){
		document.include("dynelement");
	}	
</script>
<style>
TD {
	background-image: 				url("media/images/misc/buttonbar.gif");
	background-position: 			top;
	background-repeat: 				repeat-x;	
}

</style>
<script type="text/javascript">

var ie55 = /MSIE ((5\.[56789])|([6789]))/.test( navigator.userAgent ) &&
			navigator.platform == "Win32";

if ( !ie55 ) {
	window.onerror = function () {
		return true;
	};
}

function writeNotSupported() {
	if ( !ie55 ) {
		document.write( "<p class=\"warning\">" +
			"This script only works in Internet Explorer 5.5" +
			" or greater for Windows</p>" );
	}
}

function removeWait() {
	try {
		parent.topFrame.document.getElementById('buildText').innerHTML='';
	} catch(oException) {
		x=window.setTimeout('removeWait()', 1000);
	}
}

function openCredits() {
	parent.main.document.location.href = "index.php?a=18";
	xwwd = window.setTimeout('stopIt()', 2000);
}

function stopIt() {
	top.scripter.stopWork();
}

</script>
<script type="text/javascript" src="media/script/iemenu/poslib.js"></script>
<script type="text/javascript" src="media/script/iemenu/scrollbutton.js"></script>
<script type="text/javascript" src="media/script/iemenu/menu4.js"></script>
<style>
BODY {
	margin:							0px 0px 0px 0px;
}

</style>
</head>
<body onload="removeWait();">

<script type="text/javascript">
function launch() {
	window.open('../');
}
</script>

<?php
// function to read and return document templates as nodes
function getTemplates() {
	global $modx;
	global $dbase, $table_prefix;
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_templates ORDER BY templatename ASC;"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs); 
	for ($i = 0; $i < $limit; $i++) { 
		$row=mysql_fetch_assoc($rs); 
		// echo the data retrieved 
		echo "editTemplatesMenu.add(tmp2 = new MenuItem('".$row['templatename']."', 'index.php?id=".$row['id']."&a=16', 'media/images/icons/template16.gif'));
		tmp2.target = 'main';
		";
		echo !$modx->hasPermission('edit_template')  ? "tmp2.disabled=true\n" : "" ;
	} 
}

// function to read and return snippets as nodes
function getSnippets() { 
	global $modx;
	global $dbase, $table_prefix;
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_snippets ORDER BY name ASC;"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs); 
	for ($i = 0; $i < $limit; $i++) { 
		$row=mysql_fetch_assoc($rs); 
		// echo the data retrieved 
		echo "editSnippetsMenu.add(tmp2 = new MenuItem('<span style=\"color:#888\">[[</span>&nbsp;".$row['name']."&nbsp;<span style=\"color:#888\">]]</span>', 'index.php?id=".$row['id']."&a=22', 'media/images/icons/menu_settings.gif'));
		tmp2.target = 'main';
		";
		echo !$modx->hasPermission('edit_snippet') ? "tmp2.disabled=true\n" : "" ;
	} 
}

// function to read and return snippets as nodes
function getHTMLSnippets() { 
	global $modx;
	global $dbase, $table_prefix;
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_htmlsnippets ORDER BY name ASC;"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs); 
	for ($i = 0; $i < $limit; $i++) { 
		$row=mysql_fetch_assoc($rs); 
		// echo the data retrieved 
		echo "editHTMLSnippetsMenu.add(tmp2 = new MenuItem('<span style=\"color:#888\">{{</span>&nbsp;".$row['name']."&nbsp;<span style=\"color:#888\">}}</span>', 'index.php?id=".$row['id']."&a=78', 'media/images/icons/menu_settings.gif'));
		tmp2.target = 'main';
		";
		echo !$modx->hasPermission('edit_snippet') ? "tmp2.disabled=true\n" : "" ;
	} 
}

// function to read and return users as nodes
function getUsers() { 
	global $modx;
	global $dbase, $table_prefix;
	$sql = "SELECT * FROM $dbase.".$table_prefix."manager_users ORDER BY username ASC;"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs); 
	for ($i = 0; $i < $limit; $i++) { 
		$row=mysql_fetch_assoc($rs); 
		// echo the data retrieved 
		echo "editUsersMenu.add(tmp2 = new MenuItem('".$row['username']."', 'index.php?id=".$row['id']."&a=12&n=".$row['username']."', 'media/images/icons/user.gif'));
		tmp2.target = 'main';
		";
		echo !$modx->hasPermission('edit_user') ? "tmp2.disabled=true\n" : "" ;
	} 
}

// function to read and return roles as nodes
function getRoles() { 
	global $modx;
	global $dbase, $table_prefix;
	$sql = "SELECT * FROM $dbase.".$table_prefix."user_roles ORDER BY name ASC;"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs); 
	for ($i = 0; $i < $limit; $i++) { 
		$row=mysql_fetch_assoc($rs); 
		// echo the data retrieved 
		echo "editRolesMenu.add(tmp2 = new MenuItem('".$row['name']."', 'index.php?id=".$row['id']."&a=35', 'media/images/icons/user.gif'));
		tmp2.target = 'main';
		";
		echo (!$modx->hasPermission('edit_role') || $row['id']==1) ? "tmp2.disabled=true\n" : "" ;
	} 
}

// Modified By Raymond: Template Variabled - based on Apodigm DocVars - web@apodigm.com
// function to read and return Template Variables as nodes
function getTmplVars() { 
	global $modx;
	global $dbase, $table_prefix;
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_tmplvars ORDER BY name ASC;"; 
	$rs = mysql_query($sql); 
	$limit = mysql_num_rows($rs); 
	for ($i = 0; $i < $limit; $i++) { 
		$row=mysql_fetch_assoc($rs); 
		// echo the data retrieved 
		echo "editTmplVarsMenu.add(tmp2 = new MenuItem('<span style=\"color:#888\">[*</span>&nbsp;".($row['caption']? $row['caption']:$row['name'])."&nbsp;<span style=\"color:#888\">*]</span>', 'index.php?id=".$row['id']."&a=301', 'media/images/icons/menu_settings.gif'));
		tmp2.target = 'main';
		";
		echo !$modx->hasPermission('edit_template') ? "tmp2.disabled=true\n" : "" ;
	} 
}
//End modification

?>


<script type="text/javascript">


Menu.prototype.cssFile = "media/script/iemenu/officexp/officexp.css";
Menu.prototype.mouseHoverDisabled = false;

var tmp;
var tmp2;
var mb = new MenuBar;

///////////////////////////////////////////////////////////////////////////////
// Site Menu
//

var siteMenu = new Menu();

siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['home']; ?>", "index.php?a=2", "media/images/icons/home.gif") );
	tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['launch_site']; ?>", "javascript:launch();", "media/images/icons/new4-0591.gif") );
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['refresh_site']; ?>", "index.php?a=26", "media/images/icons/refresh.gif") );
	tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['site_schedule']; ?>", "index.php?a=70", "media/images/icons/date.gif") );
	tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['visitor_stats']; ?>", "index.php?a=68", "media/images/icons/context_view.gif") );
	tmp.target="main";
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['visitor_stats_online']; ?>", "index.php?a=69", "media/images/icons/context_view.gif") );
	tmp.target="main";
siteMenu.add( new MenuSeparator() );
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['export_site']; ?>", "index.php?a=83", "media/images/icons/save.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('edit_document') ? "\ttmp.disabled=true;" : "" ; ?> 
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['import_site']; ?>", "index.php?a=95", null) );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_document') ? "\ttmp.disabled=true;" : "" ; ?> 
siteMenu.add( new MenuSeparator() );
siteMenu.add( tmp = new MenuItem( "<?php echo $_lang['logout']; ?>", "index.php?a=8", "media/images/icons/delete.gif") );
	tmp.target="_top";

mb.add( tmp = new MenuButton( "<?php echo $_lang['site']; ?>", siteMenu ) );

///////////////////////////////////////////////////////////////////////////////
// Content Menu
//

var contentMenu = new Menu();

contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['add_folder']; ?>", "index.php?a=85", "media/images/icons/folder.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_document') ? "\ttmp.disabled=true;" : "" ; ?> 
contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['add_document']; ?>", "index.php?a=4", "media/images/icons/newdoc.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_document') ? "\ttmp.disabled=true;" : "" ; ?> 
contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['add_weblink']; ?>", "index.php?a=72", "media/images/icons/weblink.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_document') ? "\ttmp.disabled=true;" : "" ; ?> 
contentMenu.add( new MenuSeparator() );
contentMenu.add( tmp = new MenuItem( "<?php echo $_lang['search']; ?>", "index.php?a=71", "media/images/icons/tree_search.gif") );
	tmp.target="main";

mb.add( tmp = new MenuButton( "<?php echo $_lang['content'];?>", contentMenu ) );

///////////////////////////////////////////////////////////////////////////////
// My Account Menu
//

var myAccountMenu = new Menu();

myAccountMenu.add( tmp = new MenuItem( "<?php echo $_lang['messages']; ?>", "index.php?a=10", "media/images/icons/messages.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('messages') ? "\ttmp.disabled=true;" : "" ; ?> 
myAccountMenu.add( tmp = new MenuItem( "<?php echo $_lang['change_password']; ?>", "index.php?a=28", "media/images/icons/password.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('change_password') ? "\ttmp.disabled=true;" : "" ; ?> 

mb.add( tmp = new MenuButton( "<?php echo $_lang['my_account'];?>", myAccountMenu ) );

///////////////////////////////////////////////////////////////////////////////
// User Menus
//
	// first generate the submenus
	var editUsersMenu = new Menu();
<?php getUsers(); ?>
	
	var editRolesMenu = new Menu();
<?php getRoles(); ?>

var usersMenu = new Menu();

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['user_management_title']; ?>", "index.php?a=75", "media/images/icons/mnu_users.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_user') && !$modx->hasPermission('edit_user') ? "\ttmp.disabled=true;" : "" ; ?> 

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['web_user_management_title']; ?>", "index.php?a=99", "media/images/icons/mnu_users.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_web_user') && !$modx->hasPermission('edit_web_user') ? "\ttmp.disabled=true;" : "" ; ?> 

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_user']; ?>", "index.php?a=11", "media/images/icons/user.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_user') ? "\ttmp.disabled=true;" : "" ; ?> 
usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_user']; ?>", null, null, editUsersMenu) );
	tmp.target="main";

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['role_management_title']; ?>", "index.php?a=86") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_role') ? "\ttmp.disabled=true;" : "" ; ?> 

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_role']; ?>", "index.php?a=38", "media/images/icons/mnu_users.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_role') ? "\ttmp.disabled=true;" : "" ; ?> 
usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_role']; ?>", null, null, editRolesMenu) );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_role') ? "\ttmp.disabled=true;" : "" ; ?> 

usersMenu.add( new MenuSeparator() );

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['manager_permissions']; ?>", "index.php?a=40", null) );
	tmp.target="main";
<?php echo !$modx->hasPermission('access_permissions') ? "\ttmp.disabled=true;" : "" ; ?> 

usersMenu.add( tmp = new MenuItem( "<?php echo $_lang['web_permissions']; ?>", "index.php?a=91", null) );
	tmp.target="main";
<?php echo !$modx->hasPermission('web_access_permissions') ? "\ttmp.disabled=true;" : "" ; ?> 


mb.add( tmp = new MenuButton( "<?php echo $_lang['users'];?>", usersMenu ) );

///////////////////////////////////////////////////////////////////////////////
// 	Resources Menus
//
	// first generate the submenus
	var editTemplatesMenu = new Menu();
<?php getTemplates(); ?>
	
	var editSnippetsMenu = new Menu();
<?php getSnippets(); ?>
	
	var editHTMLSnippetsMenu = new Menu();
<?php getHTMLSnippets(); ?>

//Modified By Raymond - Template Variables - Added by Apodigm 09-06-2004- DocVars - web@apodigm.com
    var editTmplVarsMenu = new Menu();
<?php getTmplVars(); ?>
//End Modification

var resourcesMenu = new Menu();

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['module_management']; ?>", "index.php?a=19", "media/images/icons/template16.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_module') && !$modx->hasPermission('edit_module') && !$modx->hasPermission('exec_module') ? "\ttmp.disabled=true;" : "" ; ?> 

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['resource_management']; ?>", "index.php?a=76", "media/images/icons/menu_settings.gif") );
	tmp.target="main";
<?php 
	$hasAccess = $modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags');	
	echo !$hasAccess ? "\ttmp.disabled=true;" : "" ; ?> 

resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_template']; ?>", "index.php?a=19", "media/images/icons/template16.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_template') ? "\ttmp.disabled=true;" : "" ; ?> 
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_template']; ?>", null, null, editTemplatesMenu) );
	tmp.target="main";

resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_snippet']; ?>", "index.php?a=23", "media/images/icons/menu_settings.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_snippet') ? "\ttmp.disabled=true;" : "" ; ?> 
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_snippet']; ?>", null, null, editSnippetsMenu) );
	tmp.target="main";

resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_htmlsnippet']; ?>", "index.php?a=77", "media/images/icons/menu_settings.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_snippet') ? "\ttmp.disabled=true;" : "" ; ?> 
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_htmlsnippet']; ?>", null, null, editHTMLSnippetsMenu) );
	tmp.target="main";

resourcesMenu.add( new MenuSeparator() );
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['keywords']; ?>", "index.php?a=81", null) );
<?php echo !$modx->hasPermission('new_document') ? "\ttmp.disabled=true;" : "" ; ?> 
	tmp.target="main";

//Modified By Raymond - Template Variables - Added by Apodigm 09-06-2004- DocVars - web@apodigm.com
resourcesMenu.add( new MenuSeparator() );

resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['new_tmplvars']; ?>", "index.php?a=300", "media/images/icons/menu_settings.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('new_template') ? "\ttmp.disabled=true;" : "" ; ?> 
resourcesMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_tmplvars']; ?>", null, null, editTmplVarsMenu) );
	tmp.target="main";
//End modification

mb.add( tmp = new MenuButton( "<?php echo $_lang['resources'];?>", resourcesMenu ) );

//===============================================================================
// Administration menu

var administrationMenu = new Menu();

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['edit_settings']; ?>", "index.php?a=17", "media/images/icons/menu_settings.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('settings') ? "\ttmp.disabled=true;" : "" ; ?> 

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['view_sysinfo']; ?>", "index.php?a=53", "media/images/icons/sysinfo.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('settings') ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['eventlog_viewer']; ?>", "index.php?a=114", "media/images/icons/logging.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('view_eventlog') ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['view_logging']; ?>", "index.php?a=13", "media/images/icons/logging.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('logs') ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['remove_locks']; ?>", "javascript:top.scripter.removeLocks();", "media/images/icons/lock.gif") );
<?php echo !$modx->hasPermission('settings') ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['manage_files']; ?>", "index.php?a=31", "media/images/icons/folder.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('file_manager') ? "\ttmp.disabled=true;" : "" ; ?>

administrationMenu.add( tmp = new MenuItem( "<?php echo $_lang['bk_manager']; ?>", "index.php?a=93", "media/images/icons/bkmanager.gif") );
	tmp.target="main";
<?php echo !$modx->hasPermission('bk_manager') ? "\ttmp.disabled=true;" : "" ; ?> 

mb.add( tmp = new MenuButton( "<?php echo $_lang['administration'];?>", administrationMenu ) );	


//===============================================================================
// Help menu
var helpMenu = new Menu();

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['help'];?>", "index.php?a=9", "media/images/icons/b02.gif"));
tmp.target = "main";

helpMenu.add(new MenuSeparator);

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['credits'];?>", "javascript:openCredits();"));

helpMenu.add(tmp = new MenuItem("<?php echo $_lang['about'];?>", "index.php?a=59"));
tmp.target = "main";

mb.add( tmp = new MenuButton( "<?php echo $_lang['help'];?>", helpMenu ) );	


</script>
<script type="text/javascript">
writeNotSupported();
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0"  class="menu-body">
  <tr style="height: 21px;">
    <td valign="middle"><script type="text/javascript">mb.write();</script></td>
    <td id="Button1" align="right" width="1" onclick="top.location='index.php?a=8';"><?php echo $_lang["logout"]; ?></td>
		  <script>createButton(document.getElementById("Button1"));</script>
  </tr>
</table>

</body>
</html>
