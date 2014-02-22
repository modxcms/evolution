<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_plugin') && !$modx->hasPermission('save_snippet') && !$modx->hasPermission('save_template') && !$modx->hasPermission('save_module')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

    $catId = intval($_GET['catId']);

    // Set the item name for logger
    $name = $modx->db->getValue($modx->db->select('category', $modx->getFullTableName('categories'), "id='{$catId}'"));
    $_SESSION['itemname'] = $name;

    include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
    deleteCategory($catId);

$header="Location: index.php?a=76";
header($header);
?>