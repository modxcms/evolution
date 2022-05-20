<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!EvolutionCMS()->hasPermission('save_chunk')) {
    EvolutionCMS()->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = (int)$_POST['id'];
$snippet = $_POST['post'];
$name = trim($_POST['name']);
$description = $_POST['description'];
$locked = isset($_POST['locked']) && $_POST['locked'] == 'on' ? 1 : 0;
$disabled = isset($_POST['disabled']) && $_POST['disabled'] == "on" ? '1' : '0';
$createdon = $editedon = time() + EvolutionCMS()->config['server_offset_time'];

//Kyle Jaebker - added category support
if (empty($_POST['newcategory']) && $_POST['categoryid'] > 0) {
    $category = (int)$_POST['categoryid'];
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $category = 0;
} else {
    include_once(MODX_MANAGER_PATH . 'includes/categories.inc.php');
    $category = checkCategory($_POST['newcategory']);
    if (!$category) {
        $category = newCategory($_POST['newcategory']);
    }
}

if ($name == "" || $name == 'null') {
    $name = "Untitled chunk";
}

$editor_type = $_POST['which_editor'] != 'none' ? 1 : 2;
$editor_name = $_POST['which_editor'] != 'none' ? $_POST['which_editor'] : 'none';

switch ($_POST['mode']) {
    case '77':

        // invoke OnBeforeChunkFormSave event
        EvolutionCMS()->invokeEvent("OnBeforeChunkFormSave", array(
            "mode" => "new",
            "id" => $id
        ));

        // disallow duplicate names for new chunks
        if (EvolutionCMS\Models\SiteHtmlsnippet::where('name','=',$name)->first()) {
            EvolutionCMS()->getManagerApi()->saveFormValues(77);
            EvolutionCMS()->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['chunk'], $name), "index.php?a=77");
        }

        //do stuff to save the new doc
        $id = EvolutionCMS\Models\SiteHtmlsnippet::create(compact('name', 'description','snippet','locked','category','editor_type','editor_name','disabled','createdon','editedon'))->getKey();

        // invoke OnChunkFormSave event
        EvolutionCMS()->invokeEvent("OnChunkFormSave", array(
            "mode" => "new",
            "id" => $id
        ));

        // Set the item name for logger
        $_SESSION['itemname'] = $name;

        // empty cache
        EvolutionCMS()->clearCache('full');

        // finished emptying cache - redirect
        if ($_POST['stay'] != '') {
            $a = ($_POST['stay'] == '2') ? "78&id=$id" : "77";
            $header = "Location: index.php?a=" . $a . "&r=2&stay=" . $_POST['stay'];
            header($header);
        } else {
            $header = "Location: index.php?a=76&r=2";
            header($header);
        }
        break;
    case '78':
        // invoke OnBeforeChunkFormSave event
        EvolutionCMS()->invokeEvent("OnBeforeChunkFormSave", array(
            "mode" => "upd",
            "id" => $id
        ));

        // disallow duplicate names for chunks
        if (EvolutionCMS\Models\SiteHtmlsnippet::where('id','!=',$id)->where('name','=',$name)->first()) {
            EvolutionCMS()->getManagerApi()->saveFormValues(78);
            EvolutionCMS()->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['chunk'], $name), "index.php?a=78&id={$id}");
        }

        //do stuff to save the edited doc
        $chunk = EvolutionCMS\Models\SiteHtmlsnippet::find($id);

        $chunk->update(compact('name', 'description','snippet','locked','category','editor_type','editor_name','disabled','editedon'));

        // invoke OnChunkFormSave event
        EvolutionCMS()->invokeEvent("OnChunkFormSave", array(
            "mode" => "upd",
            "id" => $id
        ));

        // Set the item name for logger
        $_SESSION['itemname'] = $name;

        // empty cache
        EvolutionCMS()->clearCache('full');

        // finished emptying cache - redirect
        if ($_POST['stay'] != '') {
            $a = ($_POST['stay'] == '2') ? "78&id=$id" : "77";
            $header = "Location: index.php?a=" . $a . "&r=2&stay=" . $_POST['stay'];
            header($header);
        } else {
            EvolutionCMS()->unlockElement(3, $id);
            $header = "Location: index.php?a=76&r=2";
            header($header);
        }
        break;
    default:
        EvolutionCMS()->webAlertAndQuit("No operation set in request.");
}
