<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('settings')) {
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
