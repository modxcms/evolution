<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('exec_module')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// check if user has access permission, except admins
if($_SESSION['mgrRole']!=1){
	$rs = $modx->db->select(
		'sma.usergroup,mg.member',
		$modx->getFullTableName("site_module_access")." sma
			LEFT JOIN ".$modx->getFullTableName("member_groups")." mg ON mg.user_group = sma.usergroup AND member='".$modx->getLoginUserID()."'",
		"sma.module = '{$id}'"
		);
	//initialize permission to -1, if it stays -1 no permissions
	//attached so permission granted
	$permissionAccessInt = -1;

	while ($row = $modx->db->getRow($rs)) {
		if($row["usergroup"] && $row["member"]) {
			//if there are permissions and this member has permission, ofcourse
			//this is granted
			$permissionAccessInt = 1;
		} elseif ($permissionAccessInt==-1) {
			//if there are permissions but this member has no permission and the
			//variable was still in init state we set permission to 0; no permissions
			$permissionAccessInt = 0;
		}
	}

	if($permissionAccessInt==0) {
		$modx->webAlertAndQuit("You do not sufficient privileges to execute this module.", "index.php?a=106");
	}
}

// get module data
$rs = $modx->db->select('*', $modx->getFullTableName("site_modules"), "id='{$id}'");
$content = $modx->db->getRow($rs);
if(!$content) {
	$modx->webAlertAndQuit("No record found for id {$id}.", "index.php?a=106");
}
if($content['disabled']) {
	$modx->webAlertAndQuit("This module is disabled and cannot be executed.", "index.php?a=106");
}

// Set the item name for logger
$_SESSION['itemname'] = $content['name'];

// load module configuration
$parameter = $modx->parseProperties($content["properties"], $content["guid"], 'module');

// Set the item name for logger
$_SESSION['itemname'] = $content['name'];

echo evalModule($content["modulecode"], $parameter);
include MODX_MANAGER_PATH."includes/sysalert.display.inc.php";

/**
 * evalModule
 *
 * @param string $moduleCode
 * @param array $params
 * @return string
 */
function evalModule($moduleCode,$params){
	$modx = evolutionCMS();
	$modx->event->params = &$params; // store params inside event object
	if(is_array($params)) {
		extract($params, EXTR_SKIP);
	}
	ob_start();
	$mod = eval($moduleCode);
	$msg = ob_get_contents();
	ob_end_clean();
	if (isset($php_errormsg))
	{
		$error_info = error_get_last();
        switch($error_info['type'])
        {
        	case E_NOTICE :
        		$error_level = 1;
        	case E_USER_NOTICE :
        		break;
        	case E_DEPRECATED :
        	case E_USER_DEPRECATED :
        	case E_STRICT :
        		$error_level = 2;
        		break;
        	default:
        		$error_level = 99;
        }
		if($modx->config['error_reporting']==='99' || 2<$error_level)
		{
			$modx->messageQuit('PHP Parse Error', '', true, $error_info['type'], $error_info['file'], $_SESSION['itemname'] . ' - Module', $error_info['message'], $error_info['line'], $msg);
			$modx->event->alert("An error occurred while loading. Please see the event log for more information<p>{$msg}</p>");
		}
	}
	unset($modx->event->params);
	return $mod.$msg;
}
