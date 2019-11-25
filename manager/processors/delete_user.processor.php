<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('delete_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// delete the user, but first check if we are deleting our own record
if($id==$modx->getLoginUserID('mgr')) {
	$modx->webAlertAndQuit("You can't delete yourself!");
}

// Set the item name for logger
$username = EvolutionCMS\Models\ManagerUser::findOrFail($id)->username;
$_SESSION['itemname'] = $username;

// invoke OnBeforeUserFormDelete event
$modx->invokeEvent("OnBeforeUserFormDelete",
	array(
		"id"	=> $id
	));

// delete the user.
EvolutionCMS\Models\ManagerUser::destroy($id);

// invoke OnManagerDeleteUser event
$modx->invokeEvent("OnManagerDeleteUser",
	array(
		"userid"		=> $id,
		"username"		=> $username
	));

// invoke OnUserFormDelete event
$modx->invokeEvent("OnUserFormDelete",
	array(
		"id"	=> $id
	));

$header="Location: index.php?a=75";
header($header);
