<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('delete_eventlog')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php

$id=intval($_GET['id']);
$clearlog = ($_GET['cls']==1 ? true:false);

// delete event log
$sql = "DELETE FROM ".$modx->getFullTableName("event_log").(!$clearlog ? " WHERE id=".$id.";":"");
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the event log...";
	exit;
} else {
	$header="Location: index.php?a=114";
	header($header);
}

?>
