<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('delete_eventlog')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$query = \EvolutionCMS\Models\EventLog::query();

if (isset($_GET['cls']) && $_GET['cls']==1) {
} else {
	$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
	if($id==0) {
		$modx->webAlertAndQuit($_lang["error_no_id"]);
	}
    $query = $query->where('id', $id);
}

// delete event log

$query->delete();

$header="Location: index.php?a=114";
header($header);
