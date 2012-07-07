<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

$hasPermission = 0;
if($modx->hasPermission('save_plugin') ||
   $modx->hasPermission('save_snippet') ||
   $modx->hasPermission('save_template') ||
   $modx->hasPermission('save_module')) {
    $hasPermission = 1;
}

if ($hasPermission) {
    $catId = intval($_GET['catId']);
    include_once "categories.inc.php";
    deleteCategory($catId);
}
$header="Location: index.php?a=76";
header($header);
?>
