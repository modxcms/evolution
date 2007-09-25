<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('settings')) {
	$e->setError(3);
	$e->dumpError();
}

$savethese = array();
foreach ($_POST as $k => $v) {
	switch ($k) {
		case 'error_page':
		case 'unauthorized_page':
		if (trim($v) == '' || !is_numeric($v)) {
			$v = $_POST['site_start'];
		}
		break;

		case 'lst_custom_contenttype':
		case 'txt_custom_contenttype':
			// Skip these
			continue 2;
			break;
		default:
		break;
	}
	$v = is_array($v) ? implode(",", $v) : $v;
	if ($k == 'manager_lang_attribute' && trim($v) == '') $v = 'en';

	$savethese[] = '(\''.mysql_escape_string($k).'\', \''.mysql_escape_string($v).'\')';
}

// Run a single query to save all the values
$sql = "REPLACE INTO ".$modx->getFullTableName("system_settings")." (setting_name, setting_value)
	VALUES ".implode(', ', $savethese);
if(!@$rs = mysql_query($sql)) {
	echo "Failed to update setting value!";
	exit;
}

// Reset Template Pages - by Raymond
if (isset($_POST['reset_template'])) {
	$template = $_POST['default_template'];
	$oldtemplate = $_POST['old_template'];
	$tbl = $dbase.".`".$table_prefix."site_content`";
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
$_SESSION['mgrRefreshTheme'] = 1;
$header="Location: index.php?a=7&r=10";
header($header);
?>
