<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('remove_locks')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// Remove locks
$sql = "TRUNCATE $dbase.`".$table_prefix."active_users`";
$rs = $modx->db->query($sql);
if(!$rs) {
	$modx->webAlertAndQuit("Something went wrong while trying to remove the locks!");
}
$header="Location: index.php?a=7";
	header($header);
?>