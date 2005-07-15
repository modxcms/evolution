<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('settings') && $_REQUEST['a']==30) {
	$e->setError(3);
	$e->dumpError();	
}
foreach ($_POST as $k => $v) {
	$sql = "REPLACE INTO ".$modx->getFullTableName("system_settings")." (setting_name, setting_value) VALUES('".mysql_escape_string($k)."', '".mysql_escape_string($v)."')";
	
	if(!@$rs = mysql_query($sql)) {
		echo "Failed to update setting value!";
		exit;
	}
}

// Reset Template Pages - by Raymond
if (isset($_POST['reset_template'])) {
	$template = $_POST['default_template'];	
	$oldtemplate = $_POST['old_template'];
	$tbl = $dbase.".".$table_prefix."site_content";
	$reset = $_POST['reset_template'];
	if($reset==1) mysql_query("UPDATE $tbl SET template = '$template' WHERE type='document'");
	else if($reset==2) mysql_query("UPDATE $tbl SET template = '$template' WHERE template = $oldtemplate");	
}
// Reset Template Pages - by Raymond


// empty cache
include_once "cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache		
$header="Location: index.php?a=7&r=10";
header($header);


?>