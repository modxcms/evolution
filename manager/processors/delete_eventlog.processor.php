<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_eventlog')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id=intval($_GET['id']);
$clearlog = ($_GET['cls']==1 ? true:false);

// delete event log
$modx->db->delete($modx->getFullTableName("event_log"), ($clearlog ? '' : "id='{$id}'"));

	$header="Location: index.php?a=114";
	header($header);

?>