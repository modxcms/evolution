<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id=intval($_GET['id']);

// delete the user, but first check if we are deleting our own record
if($id==$modx->getLoginUserID()) {
	$modx->webAlertAndQuit("You can't delete yourself!");
}

// get user name
$sql = "SELECT * FROM $dbase.`".$table_prefix."manager_users` WHERE $dbase.`".$table_prefix."manager_users`.id='".$id."' LIMIT 1;";
$rs = $modx->db->query($sql);
	$row = $modx->db->getRow($rs);
	$username = $row['username'];

// invoke OnBeforeUserFormDelete event
$modx->invokeEvent("OnBeforeUserFormDelete",
						array(
							"id"	=> $id
						));

// Set the item name for logger
$_SESSION['itemname'] = $username;

//ok, delete the user.
$sql = "DELETE FROM $dbase.`".$table_prefix."manager_users` WHERE $dbase.`".$table_prefix."manager_users`.id=".$id.";";
$modx->db->query($sql);

$sql = "DELETE FROM $dbase.`".$table_prefix."member_groups` WHERE $dbase.`".$table_prefix."member_groups`.member=".$id.";";
$modx->db->query($sql);

// delete user settings
$sql = "DELETE FROM $dbase.`".$table_prefix."user_settings` WHERE $dbase.`".$table_prefix."user_settings`.user=".$id.";";
$modx->db->query($sql);

// delete the attributes
$sql = "DELETE FROM $dbase.`".$table_prefix."user_attributes` WHERE $dbase.`".$table_prefix."user_attributes`.internalKey=".$id.";";
$modx->db->query($sql);

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
?>