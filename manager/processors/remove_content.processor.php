<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('delete_document')) {
	$e->setError(3);
	$e->dumpError();
}

$sql = "SELECT id FROM $dbase.`".$table_prefix."site_content` WHERE $dbase.`".$table_prefix."site_content`.deleted=1;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
$ids = array();
if($limit>0) {
	for($i=0;$i<$limit;$i++) {
		$row=mysql_fetch_assoc($rs);
		array_push($ids, @$row['id']);
	}
}

// invoke OnBeforeEmptyTrash event
$modx->invokeEvent("OnBeforeEmptyTrash",
						array(
							"ids"=>$ids
						));

// remove the document groups link.
$sql = "DELETE $dbase.`".$table_prefix."document_groups`
		FROM $dbase.`".$table_prefix."document_groups`
		INNER JOIN $dbase.`".$table_prefix."site_content` ON $dbase.`".$table_prefix."site_content`.id = $dbase.`".$table_prefix."document_groups`.document
		WHERE $dbase.`".$table_prefix."site_content`.deleted=1;";
@mysql_query($sql);

// remove the TV content values.
$sql = "DELETE $dbase.`".$table_prefix."site_tmplvar_contentvalues`
		FROM $dbase.`".$table_prefix."site_tmplvar_contentvalues`
		INNER JOIN $dbase.`".$table_prefix."site_content` ON $dbase.`".$table_prefix."site_content`.id = $dbase.`".$table_prefix."site_tmplvar_contentvalues`.contentid
		WHERE $dbase.`".$table_prefix."site_content`.deleted=1;";
@mysql_query($sql);

//'undelete' the document.
$sql = "DELETE FROM $dbase.`".$table_prefix."site_content` WHERE deleted=1;";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to remove deleted documents!";
	exit;
} else {
	// invoke OnEmptyTrash event
	$modx->invokeEvent("OnEmptyTrash",
						array(
							"ids"=>$ids
						));

	// empty cache
	include_once "cache_sync.class.processor.php";
	$sync = new synccache();
	$sync->setCachepath("../assets/cache/");
	$sync->setReport(false);
	$sync->emptyCache(); // first empty the cache
	// finished emptying cache - redirect
	$header="Location: index.php?r=1&a=7";
	header($header);
}
?>
