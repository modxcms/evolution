<?php
if (IN_MANAGER_MODE != "true")
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if (!$modx->hasPermission('save_role')) {
    $e->setError(3);
    $e->dumpError();
}

foreach ($_POST as $n => $v)
    $_POST[$n] = $modx->db->escape($v); // escape post values 
extract($_POST);

if ($name == '' || !isset ($name)) {
    echo "Please enter a name for this role!";
    exit;
}

// setup fields
$fields = array (
    'name' => $name,
    'description' => $description,
    'frames' => $frames,
    'home' => $home,
    'view_document' => $view_document,
    'new_document' => $new_document,
    'save_document' => $save_document,
    'publish_document' => $publish_document,
    'delete_document' => $delete_document,
    'empty_trash' => $empty_trash,
    'action_ok' => $action_ok,
    'logout' => $logout,
    'help' => $help,
    'messages' => $messages,
    'new_user' => $new_user,
    'edit_user' => $edit_user,
    'logs' => $logs,
    'edit_parser' => (isset ($edit_parser)) ? $edit_parser : '0',
    'save_parser' => (isset ($save_parser)) ? $save_parser : '0',
    'edit_template' => $edit_template,
    'settings' => $settings,
    'credits' => $credits,
    'new_template' => $new_template,
    'save_template' => $save_template,
    'delete_template' => $delete_template,
    'edit_snippet' => $edit_snippet,
    'new_snippet' => $new_snippet,
    'save_snippet' => $save_snippet,
    'delete_snippet' => $delete_snippet,
    'edit_chunk' => $edit_chunk,
    'new_chunk' => $new_chunk,
    'save_chunk' => $save_chunk,
    'delete_chunk' => $delete_chunk,
    'empty_cache' => $empty_cache,
    'edit_document' => $edit_document,
    'change_password' => $change_password,
    'error_dialog' => $error_dialog,
    'about' => $about,
    'file_manager' => $file_manager,
    'save_user' => $save_user,
    'delete_user' => $delete_user,
    'save_password' => $save_password,
    'edit_role' => $edit_role,
    'save_role' => $save_role,
    'delete_role' => $delete_role,
    'new_role' => $new_role,
    'access_permissions' => $access_permissions,
    'bk_manager' => $bk_manager,
    'new_plugin' => $new_plugin,
    'edit_plugin' => $edit_plugin,
    'save_plugin' => $save_plugin,
    'delete_plugin' => $delete_plugin,
    'new_module' => $new_module,
    'edit_module' => $edit_module,
    'save_module' => $save_module,
    'delete_module' => $delete_module,
    'exec_module' => $exec_module,
    'view_eventlog' => $view_eventlog,
    'delete_eventlog' => $delete_eventlog,
    'manage_metatags' => $manage_metatags,
    'edit_doc_metatags' => $edit_doc_metatags,
    'new_web_user' => $new_web_user,
    'edit_web_user' => $edit_web_user,
    'save_web_user' => $save_web_user,
    'delete_web_user' => $delete_web_user,
    'web_access_permissions' => $web_access_permissions,
    'view_unpublished' => $view_unpublished,
	'import_static' => $import_static,
	'export_static' => $export_static,
    'remove_locks' => $remove_locks
);

switch ($_POST['mode']) {
    case '38' :
        $tbl = $modx->getFullTableName("user_roles");
        $rs = $modx->db->insert($fields, $tbl);
        if (!$rs) {
            echo "An error occured while attempting to save the new role.<p>";
            exit;
        }
        $header = "Location: index.php?a=86&r=2";
        header($header);
        break;
    case '35' :
        $tbl = $modx->getFullTableName("user_roles");
        $rs = $modx->db->update($fields, $tbl, "id=$id");
        if (!$rs = $modx->db->query($sql)) {
            echo "An error occured while attempting to update the role. <br />" . $modx->db->getLastError();
            exit;
        }
        $header = "Location: index.php?a=86&r=2";
        header($header);
        break;
    default :
    	echo "Erm... You supposed to be here now?";
        exit;
}
?>