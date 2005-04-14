<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['settings']!=1 && $_REQUEST['a']==54) {	$e->setError(3);
	$e->dumpError();	
}

if($_REQUEST['t']=="" || !isset($_REQUEST['t'])) {
		$e->setError(10);
		$e->dumpError();
}

$sql = "OPTIMIZE TABLE $dbase.".$_REQUEST['t'].";";
$rs = @mysql_query($sql);

$header="Location: index.php?a=53&s=4";
header($header);

?>