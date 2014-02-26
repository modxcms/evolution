<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('new_module')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? intval($_GET['id']) : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// create globally unique identifiers (guid)
function createGUID(){
	srand((double)microtime()*1000000);
	$r = rand() ;
	$u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
	$m = md5 ($u);
	return $m;
}

// duplicate module
$newid = $modx->db->insert(
	array(
		'name'=>'',
		'description'=>'',
		'disabled'=>'',
		'category'=>'',
		'wrap'=>'',
		'icon'=>'',
		'enable_resource'=>'',
		'resourcefile'=>'',
		'createdon'=>'',
		'editedon'=>'',
		'guid'=>'',
		'enable_sharedparams'=>'',
		'properties'=>'',
		'modulecode'=>'',
		), $modx->getFullTableName('site_modules'), // Insert into
	"CONCAT('Duplicate of ',name) AS name, description, disabled, category, wrap, icon, enable_resource, resourcefile, createdon, editedon, '".createGUID()."' as guid, enable_sharedparams, properties, modulecode", $modx->getFullTableName('site_modules'), "id='{$id}'"); // Copy from

// duplicate module dependencies
$modx->db->insert(
	array(
		'module'=>'',
		'resource'=>'',
		'type'=>'',
		), $modx->getFullTableName('site_module_depobj'), // Insert into
	"'{$newid}', resource, type", $modx->getFullTableName('site_module_depobj'), "module='{$id}'"); // Copy from

// duplicate module user group access
$modx->db->insert(
	array(
		'module'=>'',
		'usergroup'=>'',
		), $modx->getFullTableName('site_module_access'), // Insert into
	"'{$newid}', usergroup", $modx->getFullTableName('site_module_access'), "module='{$id}'"); // Copy from

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_modules'), "id='{$newid}'"));
$_SESSION['itemname'] = $name;

// finish duplicating - redirect to new module
$header="Location: index.php?r=2&a=108&id=$newid";
header($header);
?>