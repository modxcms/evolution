<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('remove_locks')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// Remove locks
$modx->db->truncate($modx->getFullTableName('active_users'));

$header="Location: index.php?a=7";
	header($header);
?>