<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('save_template')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = (int)$_POST['id'];
$template = $modx->db->escape($_POST['post']);
$templatename = $modx->db->escape(trim($_POST['templatename']));
$description = $modx->db->escape($_POST['description']);
$locked = $_POST['locked'] == 'on' ? 1 : 0;
$selectable = $id == $modx->config['default_template'] ? 1 :    // Force selectable
    $_POST['selectable'] == 'on' ? 1 : 0;
$currentdate = time() + $modx->config['server_offset_time'];

//Kyle Jaebker - added category support
if (empty($_POST['newcategory']) && $_POST['categoryid'] > 0) {
    $categoryid = (int)$_POST['categoryid'];
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $categoryid = 0;
} else {
    include_once(MODX_MANAGER_PATH . 'includes/categories.inc.php');
    $categoryid = checkCategory($_POST['newcategory']);
    if (!$categoryid) {
        $categoryid = newCategory($_POST['newcategory']);
    }
}

if ($templatename == "") {
    $templatename = "Untitled template";
}

switch ($_POST['mode']) {
    case '19':

        // invoke OnBeforeTempFormSave event
        $modx->invokeEvent("OnBeforeTempFormSave", array(
            "mode" => "new",
            "id" => $id
        ));

        // disallow duplicate names for new templates
        $rs = $modx->db->select('COUNT(id)', $modx->getFullTableName('site_templates'), "templatename='{$templatename}'");
        $count = $modx->db->getValue($rs);
        if ($count > 0) {
            $modx->manager->saveFormValues(19);
            $modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['template'], $templatename), "index.php?a=19");
        }

        //do stuff to save the new doc
        $newid = $modx->db->insert(array(
            'templatename' => $templatename,
            'description' => $description,
            'content' => $template,
            'locked' => $locked,
            'selectable' => $selectable,
            'category' => $categoryid,
            'createdon' => $currentdate,
            'editedon' => $currentdate
        ), $modx->getFullTableName('site_templates'));

        // invoke OnTempFormSave event
        $modx->invokeEvent("OnTempFormSave", array(
            "mode" => "new",
            "id" => $newid
        ));
        // Set new assigned Tvs
        saveTemplateAccess($newid);

        // Set the item name for logger
        $_SESSION['itemname'] = $templatename;

        // empty cache
        $modx->clearCache('full');

        // finished emptying cache - redirect
        if ($_POST['stay'] != '') {
            $a = ($_POST['stay'] == '2') ? "16&id=$newid" : "19";
            $header = "Location: index.php?a=" . $a . "&r=2&stay=" . $_POST['stay'];
            header($header);
        } else {
            $header = "Location: index.php?a=76&r=2";
            header($header);
        }

        break;
    case '16':

        // invoke OnBeforeTempFormSave event
        $modx->invokeEvent("OnBeforeTempFormSave", array(
            "mode" => "upd",
            "id" => $id
        ));

        // disallow duplicate names for templates
        $rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_templates'), "templatename='{$templatename}' AND id!='{$id}'");
        $count = $modx->db->getValue($rs);
        if ($count > 0) {
            $modx->manager->saveFormValues(16);
            $modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['template'], $templatename), "index.php?a=16&id={$id}");
        }

        //do stuff to save the edited doc
        $modx->db->update(array(
            'templatename' => $templatename,
            'description' => $description,
            'content' => $template,
            'locked' => $locked,
            'selectable' => $selectable,
            'category' => $categoryid,
            'editedon' => $currentdate
        ), $modx->getFullTableName('site_templates'), "id='{$id}'");
        // Set new assigned Tvs
        saveTemplateAccess($id);

        // invoke OnTempFormSave event
        $modx->invokeEvent("OnTempFormSave", array(
            "mode" => "upd",
            "id" => $id
        ));

        // Set the item name for logger
        $_SESSION['itemname'] = $templatename;

        // first empty the cache
        $modx->clearCache('full');

        // finished emptying cache - redirect
        if ($_POST['stay'] != '') {
            $a = ($_POST['stay'] == '2') ? "16&id=$id" : "19";
            $header = "Location: index.php?a=" . $a . "&r=2&stay=" . $_POST['stay'];
            header($header);
        } else {
            $modx->unlockElement(1, $id);
            $header = "Location: index.php?a=76&r=2";
            header($header);
        }


        break;
    default:
        $modx->webAlertAndQuit("No operation set in request.");
}

/**
 * @param int $id
 */
function saveTemplateAccess($id)
{
    global $modx;
    if ($_POST['tvsDirty'] == 1) {
        $newAssignedTvs = $_POST['assignedTv'];

        // Preserve rankings of already assigned TVs
        $rs = $modx->db->select("tmplvarid, rank", $modx->getFullTableName('site_tmplvar_templates'), "templateid='{$id}'", "");

        $ranksArr = array();
        $highest = 0;
        while ($row = $modx->db->getRow($rs)) {
            $ranksArr[$row['tmplvarid']] = $row['rank'];
            $highest = $highest < $row['rank'] ? $row['rank'] : $highest;
        };

        $modx->db->delete($modx->getFullTableName('site_tmplvar_templates'), "templateid='{$id}'");
        if (empty($newAssignedTvs)) {
            return;
        }
        foreach ($newAssignedTvs as $tvid) {
            if (!$id || !$tvid) {
                continue;
            }    // Dont link zeros
            $modx->db->insert(array(
                'templateid' => $id,
                'tmplvarid' => $tvid,
                'rank' => isset($ranksArr[$tvid]) ? $ranksArr[$tvid] : $highest += 1 // append TVs to rank
            ), $modx->getFullTableName('site_tmplvar_templates'));
        }
    }
}
