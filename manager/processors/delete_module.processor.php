<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_module')) {
	$e->setError(3);
	$e->dumpError();
}

$id=intval($_GET['id']);

// invoke OnBeforeModFormDelete event
$modx->invokeEvent("OnBeforeModFormDelete",
						array(
							"id"	=> $id
						));

//ok, delete the module.
$sql = "DELETE FROM ".$modx->getFullTableName("site_modules")." WHERE id=".$id.";";
$rs = $modx->db->query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the module...";
	exit;
}

//ok, delete the module dependencies.
$sql = "DELETE FROM ".$modx->getFullTableName("site_module_depobj")." WHERE module='".$id."';";
$rs = $modx->db->query($sql);

//ok, delete the module user group access.
$sql = "DELETE FROM ".$modx->getFullTableName("site_module_access")." WHERE module='".$id."';";
$rs = $modx->db->query($sql);

// invoke OnModFormDelete event
$modx->invokeEvent("OnModFormDelete",
							array(
								"id"	=> $id
							));

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_modules'), "id='{$id}'"));
$_SESSION['itemname'] = $name;

// empty cache
$modx->clearCache('full');
	
// finished emptying cache - redirect

$header="Location: index.php?a=106&r=2";
header($header);
?>