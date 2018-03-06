<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('new_plugin')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? (int)$_GET['id'] : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// count duplicates
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_plugins'), "id='{$id}'"));
$count = $modx->db->getRecordCount($modx->db->select('name', $modx->getFullTableName('site_plugins'), "name LIKE '{$name} {$_lang['duplicated_el_suffix']}%'"));
if($count>=1) $count = ' '.($count+1);
else $count = '';

// duplicate Plugin
$newid = $modx->db->insert(
	array(
		'name'=>'',
		'description'=>'',
		'disabled'=>'',
		'moduleguid'=>'',
		'plugincode'=>'',
		'properties'=>'',
		'category'=>'',
		), $modx->getFullTableName('site_plugins'), // Insert into
	"CONCAT(name, ' {$_lang['duplicated_el_suffix']}{$count}') AS name, description, '1' AS disabled, moduleguid, plugincode, properties, category", $modx->getFullTableName('site_plugins'), "id='{$id}'"); // Copy from

// duplicate Plugin Event Listeners
$modx->db->insert(
	array(
		'pluginid'=>'',
		'evtid'=>'',
		'priority'=>'',
		), $modx->getFullTableName('site_plugin_events'), // Insert into
	"'{$newid}', evtid, priority", $modx->getFullTableName('site_plugin_events'), "pluginid='{$id}'"); // Copy from

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_plugins'), "id='{$newid}'"));
$_SESSION['itemname'] = $name;

// finish duplicating - redirect to new plugin
$header="Location: index.php?r=2&a=102&id=$newid";
header($header);
