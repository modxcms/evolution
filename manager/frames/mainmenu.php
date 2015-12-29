<?php
/*
menu->Build('id','parent','name','link','alt','onclick','permission','target','divider 1/0','menuindex', 'class')
*/

//mainMenu
$sitemenu['site']     = array('site','main',$_lang['site'],'#site',$_lang['site'],'new NavToggle(this); return false;','','',0,10, 'active');
$sitemenu['elements']     = array('elements','main',$_lang['elements'],'#elements',$_lang['elements'],'new NavToggle(this); return false;','','',0,20, '');
$sitemenu['modules']     = array('modules','main',$_lang['modules'],'#modules',$_lang['modules'],'new NavToggle(this); return false;','','',0,30, '');
$sitemenu['users']     = array('users','main',$_lang['users'],'#users',$_lang['users'],'new NavToggle(this); return false;','edit_user','',0,40, '');
$sitemenu['tools']     = array('tools','main',$_lang['tools'],'#tools',$_lang['tools'],'new NavToggle(this); return false;','','',0,50, '');
$sitemenu['reports']     = array('reports','main',$_lang['reports'],'#reports',$_lang['reports'],'new NavToggle(this); return false;','','',0,60, '');

// Site Menu
$sitemenu['home']     = array('home','site',$_lang['home'],'index.php?a=2',$_lang['home'],'this.blur();','','main',0,10,'');
$sitemenu['preview']     = array('preview','site',$_lang['preview'],'../',$_lang['preview'],'this.blur();','','_blank',0,20,'');
$sitemenu['refresh_site']     = array('refresh_site','site',$_lang['refresh_site'],'index.php?a=26',$_lang['refresh_site'],'this.blur();','','main',0,30,'');
$sitemenu['search']     = array('search','site',$_lang['search'],'index.php?a=71',$_lang['search'],'this.blur();','','main',1,40,'');
$sitemenu['add_resource']     = array('add_resource','site',$_lang['add_resource'],'index.php?a=4',$_lang['add_resource'],'this.blur();','new_document','main',0,50,'');
$sitemenu['add_weblink']     = array('add_weblink','site',$_lang['add_weblink'],'index.php?a=72',$_lang['add_weblink'],'this.blur();','new_document','main',0,60,'');


// Elements Menu
$sitemenu['element_management']     = array('element_templates','elements',$_lang['element_management'],'index.php?a=76',$_lang['element_management'],'this.blur();','new_template,edit_template,new_snippet,edit_snippet,new_chunk,edit_chunk,new_plugin,edit_plugin','main',0,10,'');
//$sitemenu['element_templates']     = array('element_templates','elements',$_lang['manage_templates'],'index.php?a=76&tab=0',$_lang['manage_templates'],'this.blur();','new_template,edit_template','main',0,10,'');
//$sitemenu['element_tplvars']     = array('element_tplvars','elements',$_lang['tmplvars'],'index.php?a=76&tab=1',$_lang['tmplvars'],'this.blur();','new_template,edit_template','main',0,20,'');
//$sitemenu['element_htmlsnippets']     = array('element_htmlsnippets','elements',$_lang['manage_htmlsnippets'],'index.php?a=76&tab=2',$_lang['manage_htmlsnippets'],'this.blur();','new_chunk,edit_chunk','main',0,30,'');
//$sitemenu['element_snippets']     = array('element_snippets','elements',$_lang['manage_snippets'],'index.php?a=76&tab=3',$_lang['manage_snippets'],'this.blur();','new_snippet,edit_snippet','main',0,40,'');
//$sitemenu['element_plugins']     = array('element_plugins','elements',$_lang['manage_plugins'],'index.php?a=76&tab=4',$_lang['manage_plugins'],'this.blur();','new_plugin,edit_plugin','main',0,50,'');
//$sitemenu['element_categories']     = array('element_categories','elements',$_lang['element_categories'],'index.php?a=76&tab=5',$_lang['element_categories'],'this.blur();','new_template,edit_template,new_snippet,edit_snippet,new_chunk,edit_chunk,new_plugin,edit_plugin','main',1,60,'');
$sitemenu['manage_files']     = array('manage_files','elements',$_lang['manage_files'],'index.php?a=31',$_lang['manage_files'],'this.blur();','file_manager','main',0,70,'');

// Modules Menu Items
$sitemenu['new_module']     = array('new_module','modules',$_lang['module_management'],'index.php?a=106',$_lang['module_management'],'this.blur();','new_module,edit_module','main',1,0,'');
if($modx->hasPermission('exec_module')) {
	if ($_SESSION['mgrRole'] != 1) {
		$rs = $modx->db->query('SELECT DISTINCT sm.id, sm.name, mg.member
				FROM '.$modx->getFullTableName('site_modules').' AS sm
				LEFT JOIN '.$modx->getFullTableName('site_module_access').' AS sma ON sma.module = sm.id
				LEFT JOIN '.$modx->getFullTableName('member_groups').' AS mg ON sma.usergroup = mg.user_group
				WHERE (mg.member IS NULL OR mg.member = '.$modx->getLoginUserID().') AND sm.disabled != 1');
	} else {
		$rs = $modx->db->select('*', $modx->getFullTableName('site_modules'), 'disabled != 1');
	}
	$i=10;
	while ($content = $modx->db->getRow($rs)) {
		$sitemenu['module'.$content['id']]     = array('module'.$content['id'],'modules',$content['name'],'index.php?a=112&id='.$content['id'],$content['name'],'this.blur();','','main',0,0,$i+10,'');
		$i=$i+10;
	}
}

// security menu items (users)
$sitemenu['user_management_title']     = array('user_management_title','users',$_lang['user_management_title'],'index.php?a=75',$_lang['user_management_title'],'this.blur();','edit_user','main',0,10,'');
$sitemenu['web_user_management_title']     = array('web_user_management_title','users',$_lang['web_user_management_title'],'index.php?a=99',$_lang['web_user_management_title'],'this.blur();','edit_web_user','main',0,20,'');
$sitemenu['role_management_title']     = array('role_management_title','users',$_lang['role_management_title'],'index.php?a=86',$_lang['role_management_title'],'this.blur();','new_role,edit_role,delete_role','main',0,30,'');
$sitemenu['manager_permissions']     = array('manager_permissions','users',$_lang['manager_permissions'],'index.php?a=40',$_lang['manager_permissions'],'this.blur();','access_permissions','main',0,40,'');
$sitemenu['web_permissions']     = array('web_permissions','users',$_lang['web_permissions'],'index.php?a=91',$_lang['web_permissions'],'this.blur();','web_access_permissions','main',0,50,'');

// Tools Menu
$sitemenu['bk_manager']     = array('bk_manager','tools',$_lang['bk_manager'],'index.php?a=93',$_lang['bk_manager'],'this.blur();','bk_manager','main',0,10,'');
$sitemenu['remove_locks']     = array('remove_locks','tools',$_lang['remove_locks'],'javascript:removeLocks();',$_lang['remove_locks'],'this.blur();','remove_locks','',0,20,'');
$sitemenu['import_site']     = array('import_site','tools',$_lang['import_site'],'index.php?a=95',$_lang['import_site'],'this.blur();','import_static','main',0,30,'');
$sitemenu['export_site']     = array('export_site','tools',$_lang['export_site'],'index.php?a=83',$_lang['export_site'],'this.blur();','export_static','main',1,40,'');
$sitemenu['edit_settings']     = array('edit_settings','tools',$_lang['edit_settings'],'index.php?a=17',$_lang['edit_settings'],'this.blur();','settings','main',0,50,'');

// Reports Menu
$sitemenu['site_schedule']     = array('site_schedule','reports',$_lang['site_schedule'],'index.php?a=70',$_lang['site_schedule'],'this.blur();','','main',0,10,'');
$sitemenu['eventlog_viewer']     = array('eventlog_viewer','reports',$_lang['eventlog_viewer'],'index.php?a=114',$_lang['eventlog_viewer'],'this.blur();','view_eventlog','main',0,20,'');
$sitemenu['view_logging']     = array('view_logging','reports',$_lang['view_logging'],'index.php?a=13',$_lang['view_logging'],'this.blur();','logs','main',0,30,'');
$sitemenu['view_sysinfo']     = array('view_sysinfo','reports',$_lang['view_sysinfo'],'index.php?a=53',$_lang['view_sysinfo'],'this.blur();','logs','main',0,40,'');


$menu = $modx->invokeEvent("OnManagerMenuPrerender", array('menu'=>$sitemenu) ) ;
$menu = unserialize($menu[0]);
if (is_array($menu)) $sitemenu = $menu;				
include_once(MODX_MANAGER_PATH.'includes/menu.class.inc.php');
$menu = new EVOmenu();
$menu->Build($sitemenu);

?>