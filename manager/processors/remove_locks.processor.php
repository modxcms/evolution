<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('remove_locks')) {
	$e->setError(3);
	$e->dumpError();
}

// Remove locks
$sql = "TRUNCATE $dbase.`".$table_prefix."active_users`";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to remove the locks!";
	exit;
}
$header="Location: index.php?a=7";
	header($header);
?>
