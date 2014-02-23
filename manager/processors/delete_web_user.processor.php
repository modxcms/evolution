<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_web_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id=intval($_GET['id']);

// get user name
$rs = $modx->db->select('username', $modx->getFullTableName('web_users'), "id='{$id}'");
	$username = $modx->db->getValue($rs);


// invoke OnBeforeWUsrFormDelete event
$modx->invokeEvent("OnBeforeWUsrFormDelete",
					array(
						"id"	=> $id
					));

// Set the item name for logger
$_SESSION['itemname'] = $username;

// delete the user.
$modx->db->delete($modx->getFullTableName('web_users'), "id='{$id}'");

// delete user groups
$modx->db->delete($modx->getFullTableName('web_groups'), "webuser='{$id}'");

// delete the attributes
$modx->db->delete($modx->getFullTableName('web_user_attributes'), "internalKey='{$id}'");

	// invoke OnWebDeleteUser event
	$modx->invokeEvent("OnWebDeleteUser",
						array(
							"userid"		=> $id,
							"username"		=> $username
						));

	// invoke OnWUsrFormDelete event
	$modx->invokeEvent("OnWUsrFormDelete",
						array(
							"id"	=> $id
						));

	$header="Location: index.php?a=99";
	header($header);
?>