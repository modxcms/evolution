<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('remove_locks')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

if(!isset($_GET['id'])) {
	// Remove all locks
	$modx->db->truncate($modx->getFullTableName('active_user_locks'));

	$header = "Location: index.php?a=7";
	header($header);
} else {
	// Ajax: Handle single-ID unlock requests
	$type = intval($_GET['type']);
	$id = intval($_GET['id']);
	if($type && $id) {
		$modx->unlockElement($type, $id, true);
		echo '1';
		exit;
	} else {
		echo 'No type or id sent with request.';
	}
}
?>