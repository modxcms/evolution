<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!($modx->hasPermission('settings') && ($modx->hasPermission('logs')||$modx->hasPermission('bk_manager')))) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

if (isset($_REQUEST['t'])) {

	if (empty($_REQUEST['t'])) {
		$modx->webAlertAndQuit($_lang["error_no_optimise_tablename"]);
	}

	// Set the item name for logger
	$_SESSION['itemname'] = $_REQUEST['t'];

    if($modx->getDatabase()->getConfig('driver') != 'pgsql'){
	    $modx->getDatabase()->optimize($_REQUEST['t']);
    }

} elseif (isset($_REQUEST['u'])) {

	if (empty($_REQUEST['u'])) {
		$modx->webAlertAndQuit($_lang["error_no_truncate_tablename"]);
	}

	// Set the item name for logger
	$_SESSION['itemname'] = $_REQUEST['u'];
    \DB::table(\DB::raw($_REQUEST['u']))->truncate();

} else {
	$modx->webAlertAndQuit($_lang["error_no_optimise_tablename"]);
}

$mode = (int)get_by_key($_REQUEST, 'mode', 93, 'is_scalar');
$header="Location: index.php?a={$mode}&s=4";
header($header);
