<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id'])? intval($_GET['id']) : 0;
if($id==0) {
	$modx->webAlertAndQuit($_lang["error_no_id"]);
}

// duplicate TV
$newid = $modx->db->insert(
	array(
		'type'=>'',
		'name'=>'',
		'caption'=>'',
		'description'=>'',
		'default_text'=>'',
		'elements'=>'',
		'rank'=>'',
		'display'=>'',
		'display_params'=>'',
		'category'=>'',
		), $modx->getFullTableName('site_tmplvars'), // Insert into
	"type, name, CONCAT('Duplicate of ',caption) AS caption, description, default_text, elements, rank, display, display_params, category", $modx->getFullTableName('site_tmplvars'), "id='{$id}'"); // Copy from


// duplicate TV Template Access Permissions
$modx->db->insert(
	array(
		'tmplvarid'=>'',
		'templateid'=>'',
		'rank'=>'',
		), $modx->getFullTableName('site_tmplvar_templates'), // Insert into
	"'{$newid}', templateid, rank", $modx->getFullTableName('site_tmplvar_templates'), "tmplvarid='{$id}'"); // Copy from

// duplicate TV Access Permissions
$modx->db->insert(
	array(
		'tmplvarid'=>'',
		'documentgroup'=>'',
		), $modx->getFullTableName('site_tmplvar_access'), // Insert into
	"'{$newid}', documentgroup", $modx->getFullTableName('site_tmplvar_access'), "tmplvarid='{$id}'"); // Copy from

// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('name', $modx->getFullTableName('site_tmplvars'), "id='{$newid}'"));
$_SESSION['itemname'] = $name;

// finish duplicating - redirect to new variable
$header="Location: index.php?r=2&a=301&id=$newid";
header($header);
?>