<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('exec_module')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}
if (isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $id = (int)$_GET['id'];
    } else {
        $id = $_GET['id'];
    }
} else {
    $modx->webAlertAndQuit($_lang["error_no_id"]);
}
// check if user has access permission, except admins
if ($_SESSION['mgrRole'] != 1 && is_numeric($id)) {
    $moduleAccess = \EvolutionCMS\Models\SiteModuleAccess::query()
        ->leftJoin('member_groups', 'member_groups.user_group', '=', 'site_module_access.usergroup')
        ->where('site_module_access.module', $id)->where('member', $modx->getLoginUserID('mgr'));


    //initialize permission to -1, if it stays -1 no permissions
    //attached so permission granted
    $permissionAccessInt = -1;

    foreach ($moduleAccess->get()->toArray() as $row) {
        if ($row["usergroup"] && $row["member"]) {
            //if there are permissions and this member has permission, ofcourse
            //this is granted
            $permissionAccessInt = 1;
        } elseif ($permissionAccessInt == -1) {
            //if there are permissions but this member has no permission and the
            //variable was still in init state we set permission to 0; no permissions
            $permissionAccessInt = 0;
        }
    }

    if ($permissionAccessInt == 0) {
        $modx->webAlertAndQuit("You do not sufficient privileges to execute this module.", "index.php?a=106");
    }
}
if (is_numeric($id)) {
    // get module data
    $content = \EvolutionCMS\Models\SiteModule::find($id);
    if (is_null($content)) {
        $modx->webAlertAndQuit("No record found for id {$id}.", "index.php?a=106");
    }
    $content = $content->toArray();
    if ($content['disabled']) {
        $modx->webAlertAndQuit("This module is disabled and cannot be executed.", "index.php?a=106");
    }
} else {
    $content = $modx->modulesFromFile[$id];
    $content['modulecode'] = file_get_contents($content['file']);
    $content["guid"] = '';
}
// Set the item name for logger
$_SESSION['itemname'] = $content['name'];

// load module configuration
$parameter = $modx->parseProperties($content["properties"], $content["guid"], 'module');

// Set the item name for logger
$_SESSION['itemname'] = $content['name'];

if (substr($content["modulecode"], 0, 5) === '<?php') {
    $content["modulecode"] = substr($content["modulecode"], 5);
}
echo evalModule($content["modulecode"], $parameter);
include MODX_MANAGER_PATH . "includes/sysalert.display.inc.php";
