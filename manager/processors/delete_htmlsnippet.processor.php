<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_snippet')) {
	$e->setError(3);
	$e->dumpError();
}

$id=intval($_GET['id']);

// invoke OnBeforeChunkFormDelete event
$modx->invokeEvent("OnBeforeChunkFormDelete",
						array(
							"id"	=> $id
						));

//ok, delete the chunk.
$sql = "DELETE FROM $dbase.`".$table_prefix."site_htmlsnippets` WHERE $dbase.`".$table_prefix."site_htmlsnippets`.id=".$id.";";
$rs = $modx->db->query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the htmlsnippet...";
	exit;
}
// invoke OnChunkFormDelete event
$modx->invokeEvent("OnChunkFormDelete",
						array(
							"id"	=> $id
						));

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_htmlsnippets'), "id='{$id}'"));
$_SESSION['itemname'] = $name;

// empty cache
$modx->clearCache('full');

// finished emptying cache - redirect
$header="Location: index.php?a=76&r=2";
header($header);
?>