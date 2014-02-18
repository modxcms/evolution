<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_document')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$sql = "SELECT id FROM $dbase.`".$table_prefix."site_content` WHERE $dbase.`".$table_prefix."site_content`.deleted=1;";
$rs = $modx->db->query($sql);
$limit = $modx->db->getRecordCount($rs);
if($limit>0) {
	$ids = $modx->db->getColumn('id', $rs); 
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
@$modx->db->query($sql);

// remove the TV content values.
$sql = "DELETE $dbase.`".$table_prefix."site_tmplvar_contentvalues`
		FROM $dbase.`".$table_prefix."site_tmplvar_contentvalues`
		INNER JOIN $dbase.`".$table_prefix."site_content` ON $dbase.`".$table_prefix."site_content`.id = $dbase.`".$table_prefix."site_tmplvar_contentvalues`.contentid
		WHERE $dbase.`".$table_prefix."site_content`.deleted=1;";
$modx->db->query($sql);

//'undelete' the document.
$sql = "DELETE FROM $dbase.`".$table_prefix."site_content` WHERE deleted=1;";
$rs = $modx->db->query($sql);
if(!$rs) {
	$modx->webAlertAndQuit("Something went wrong while trying to remove deleted documents!");
} else {
	// invoke OnEmptyTrash event
	$modx->invokeEvent("OnEmptyTrash",
						array(
							"ids"=>$ids
						));

	// empty cache
	$modx->clearCache('full');

	// finished emptying cache - redirect
	$header="Location: index.php?r=1&a=7";
	header($header);
}
?>