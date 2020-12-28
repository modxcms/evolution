<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('new_document') || !$modx->hasPermission('save_document')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id == 0) {
    $modx->webAlertAndQuit($_lang["error_no_id"]);
}

$children = array();

// check permissions on the document
$udperms = new EvolutionCMS\Legacy\Permissions();
$udperms->user = $modx->getLoginUserID('mgr');
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];
$udperms->duplicateDoc = true;

if (!$udperms->checkPermissions()) {
    $modx->webAlertAndQuit($_lang["access_permission_denied"]);
}

// Run the duplicator
$document = \DocumentManager::duplicate(['id' => $id]);

// Set the item name for logger
$name = EvolutionCMS\Models\SiteContent::select('pagetitle')->findOrFail($document->getKey())->pagetitle;
$_SESSION['itemname'] = $name;

// finish cloning - redirect
$header = "Location: index.php?r=1&a=3&id=" . $document->getKey();
header($header);
