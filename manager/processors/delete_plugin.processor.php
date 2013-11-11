<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_plugin')) {
	$e->setError(3);
	$e->dumpError();	
}

$id=intval($_GET['id']);

// invoke OnBeforePluginFormDelete event
$modx->invokeEvent("OnBeforePluginFormDelete",
						array(
							"id"	=> $id
						));

// delete the plugin.
$sql = "DELETE FROM $dbase.`".$table_prefix."site_plugins` WHERE $dbase.`".$table_prefix."site_plugins`.id=".$id.";";
$rs = $modx->db->query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the plugin...";
	exit;
}		

// delete the plugin events.
$sql = "DELETE FROM $dbase.`".$table_prefix."site_plugin_events` WHERE $dbase.`".$table_prefix."site_plugin_events`.pluginid=".$id.";";
$rs = $modx->db->query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the plugin events...";
	exit;
}

// invoke OnPluginFormDelete event
$modx->invokeEvent("OnPluginFormDelete",
						array(
							"id"	=> $id
						));

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_plugins'), "id='{$id}'"));
$_SESSION['itemname'] = $name;

// empty cache
$modx->clearCache('full');
		
// finished emptying cache - redirect
$header="Location: index.php?a=76&r=2";
header($header);
?>