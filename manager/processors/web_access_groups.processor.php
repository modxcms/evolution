<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!EvolutionCMS()->hasPermission('web_access_permissions')) {
    EvolutionCMS()->webAlertAndQuit($_lang["error_no_privileges"]);
}

// web access group processor.
// figure out what the user wants to do...


$updategroupaccess = false;
$operation = $_REQUEST['operation'];

switch ($operation) {
    case "add_user_group" :
        $newgroup = $_REQUEST['newusergroup'];
        if (empty($newgroup)) {
            EvolutionCMS()->webAlertAndQuit("No group name specified.");
        } else {
            $id = \EvolutionCMS\Models\MembergroupName::query()->insertGetId(['name' => $newgroup]);
            // invoke OnWebCreateGroup event
            EvolutionCMS()->invokeEvent('OnWebCreateGroup', array(
                'groupid' => $id,
                'groupname' => $newgroup,
            ));
        }
        break;
    case "add_document_group" :
        $newgroup = $_REQUEST['newdocgroup'];
        if (empty($newgroup)) {
            EvolutionCMS()->webAlertAndQuit("No group name specified.");
        } else {
            $id = \EvolutionCMS\Models\DocumentgroupName::query()->insertGetId(['name' => $newgroup]);

            // invoke OnCreateDocGroup event
            EvolutionCMS()->invokeEvent('OnCreateDocGroup', array(
                'groupid' => $id,
                'groupname' => $newgroup,
            ));
        }
        break;
    case "delete_user_group" :
        $updategroupaccess = true;
        $usergroup = (int)$_REQUEST['usergroup'];
        if (empty($usergroup)) {
            EvolutionCMS()->webAlertAndQuit("No user group id specified for deletion.");
        } else {
            \EvolutionCMS\Models\MembergroupName::where('id', $usergroup)->delete();

            \EvolutionCMS\Models\MembergroupAccess::where('membergroup', $usergroup)->delete();

            \EvolutionCMS\Models\MemberGroup::where('member', $usergroup)->delete();
        }
        break;
    case "delete_document_group" :
        $group = (int)$_REQUEST['documentgroup'];
        if (empty($group)) {
            EvolutionCMS()->webAlertAndQuit("No document group id specified for deletion.");
        } else {
            \EvolutionCMS\Models\DocumentgroupName::where('id', $group)->delete();

            \EvolutionCMS\Models\MembergroupAccess::where('documentgroup', $group)->delete();

            \EvolutionCMS\Models\DocumentGroup::where('document_group', $group)->delete();
        }
        break;
    case "rename_user_group" :
        $newgroupname = $_REQUEST['newgroupname'];
        if (empty($newgroupname)) {
            EvolutionCMS()->webAlertAndQuit("No group name specified.");
        }
        $groupid = (int)$_REQUEST['groupid'];
        if (empty($groupid)) {
            EvolutionCMS()->webAlertAndQuit("No user group id specified for rename.");
        }
        \EvolutionCMS\Models\MembergroupName::where('id', $groupid)->update(['name' => $newgroupname]);
        break;
    case "rename_document_group" :
        $newgroupname = $_REQUEST['newgroupname'];
        if (empty($newgroupname)) {
            EvolutionCMS()->webAlertAndQuit("No group name specified.");
        }
        $groupid = (int)$_REQUEST['groupid'];
        if (empty($groupid)) {
            EvolutionCMS()->webAlertAndQuit("No document group id specified for rename.");
        }
        \EvolutionCMS\Models\DocumentgroupName::where('id', $groupid)->update(['name' => $newgroupname]);
        break;
    case "add_document_group_to_user_group" :
        $updategroupaccess = true;
        $usergroup = (int)($_REQUEST['usergroup'] ?? 0);
        $docgroup = (int)($_REQUEST['docgroup'] ?? 0);
        $context = (int)($_REQUEST['context'] ?? 0) == 0 ? 0 : 1;
        if (\EvolutionCMS\Models\MembergroupAccess::where('membergroup', $usergroup)->where('documentgroup', $docgroup)->where('context', $context)->count() <= 0) {
            \EvolutionCMS\Models\MembergroupAccess::create(array('membergroup' => $usergroup, 'documentgroup' => $docgroup, 'context' => $context));
        } else {
            //alert user that coupling already exists?
        }
        break;
    case "remove_document_group_from_user_group" :
        $updategroupaccess = true;
        $coupling = (int)$_REQUEST['coupling'];
        $document_group = (int)$_REQUEST['document_group'];
        $context = (int)($_REQUEST['context'] ?? 0) == 0 ? 0 : 1;
        \EvolutionCMS\Models\MembergroupAccess::where('membergroup', $coupling)->where('documentgroup', $document_group)->where('context', $context)->delete();
        break;
    default :
        EvolutionCMS()->webAlertAndQuit("No operation set in request.");
}

// secure web documents - flag as private
if ($updategroupaccess == true) {
    include MODX_MANAGER_PATH . "includes/secure_web_documents.inc.php";
    if ($context) {
        secureWebDocument();
    } else {
        secureMgrDocument();
    }
    // Update the private group column
    $columnName = $context ? 'private_webgroup' : 'private_memgroup';
    $resp = \EvolutionCMS\Models\DocumentgroupName::query()->select('documentgroup_names.id',
        'membergroup_access.membergroup')
        ->join('membergroup_access', function($join) use ($context) {
            $join->on('membergroup_access.documentgroup', '=', 'documentgroup_names.id')
                ->where('membergroup_access.context', '=', $context);
        })->get();
    foreach ($resp as $item) {
        if (!is_null($item->membergroup)) {
            \EvolutionCMS\Models\DocumentgroupName::find($item->id)->update([$columnName => 1]);
        }
    }
}

$header = "Location: index.php?a=91";
header($header);
