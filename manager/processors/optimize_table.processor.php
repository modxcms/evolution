<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!($modx->hasPermission('settings') && ($modx->hasPermission('logs')||$modx->hasPermission('bk_manager')))) {	
	$e->setError(3);
	$e->dumpError();	
}

if(($_REQUEST['t']=="" || !isset($_REQUEST['t'])) && ($_REQUEST['u']=="" || !isset($_REQUEST['u']))) {
		$e->setError(10);
		$e->dumpError();
}

if (isset($_REQUEST['t'])) $sql = "OPTIMIZE TABLE $dbase.".$_REQUEST['t'].";";
elseif (isset($_REQUEST['u'])) $sql = "TRUNCATE TABLE $dbase.".$_REQUEST['u'].";";

if($sql) $rs = @mysql_query($sql);

$mode = intval($_REQUEST['mode']);
$header="Location: index.php?a=".$mode."&s=4";
header($header);
?>
