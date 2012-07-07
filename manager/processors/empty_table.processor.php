<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('settings')) {
	$e->setError(3);
	$e->dumpError();
}

$sql = "TRUNCATE TABLE $dbase.`".$table_prefix."manager_log`";
$rs = @mysql_query($sql);

$header="Location: index.php?a=13";
header($header);
?>
