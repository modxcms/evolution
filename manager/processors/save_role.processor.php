<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['save_role']!=1 && $_REQUEST['a']==36) {	$e->setError(3);
	$e->dumpError();	
}
?>
<?php
extract($_POST);

if($name=='' || !isset($name)) {
	echo "Please enter a name for this role!";
	exit;
}

switch ($_POST['mode']) {
    case '38':
		$sql = "INSERT INTO $dbase.".$table_prefix."user_roles(name, description, frames, home, view_document, new_document,
		save_document, delete_document, action_ok, logout, help, messages, new_user, edit_user, logs,
		edit_parser, save_parser, edit_template, settings, credits, new_template, save_template,
		delete_template, edit_snippet, new_snippet, save_snippet, delete_snippet, empty_cache, edit_document,
		change_password, error_dialog, about, file_manager, save_user, delete_user, save_password,
		edit_role, save_role, delete_role, new_role, access_permissions, bk_manager, new_plugin, edit_plugin, save_plugin, delete_plugin)
				VALUES('$name', '$description', $frames, $home, $view_document, $new_document,
		$save_document, $delete_document, $action_ok, $logout, $help, $messages, $new_user, $edit_user, $logs,
		0, 0, $edit_template, $settings, $credits, $new_template, $save_template,
		$delete_template, $edit_snippet, $new_snippet, $save_snippet, $delete_snippet, $empty_cache, $edit_document,
		$change_password, $error_dialog, $about, $file_manager, $save_user, $delete_user, $save_password,
		$edit_role, $save_role, $delete_role, $new_role, $access_permissions, $bk_manager, $new_plugin, $edit_plugin, $save_plugin, $delete_plugin);";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to save the new role.<p>";
			exit;
		}
		$header="Location: index.php?a=75&r=2";
		header($header);
    break;
    case '35':
		$sql = "UPDATE $dbase.".$table_prefix."user_roles SET 
		name='$name', description='$description', frames=$frames, home=$home, view_document=$view_document, new_document=$new_document,
		save_document=$save_document, delete_document=$delete_document, action_ok=$action_ok, logout=$logout, help=$help, messages=$messages, new_user=$new_user, edit_user=$edit_user, logs=$logs,
		edit_parser=0, save_parser=0, edit_template=$edit_template, settings=$settings, credits=$credits, new_template=$new_template, save_template=$save_template,
		delete_template=$delete_template, edit_snippet=$edit_snippet, new_snippet=$new_snippet, save_snippet=$save_snippet, delete_snippet=$delete_snippet, empty_cache=$empty_cache, edit_document=$edit_document,
		change_password=$change_password, error_dialog=$error_dialog, about=$about, file_manager=$file_manager, save_user=$save_user, delete_user=$delete_user, save_password=$save_password,
		edit_role=$edit_role, save_role=$save_role, delete_role=$delete_role, new_role=$new_role, access_permissions=$access_permissions, bk_manager = $bk_manager, 
		new_plugin = $new_plugin, edit_plugin = $edit_plugin, save_plugin = $save_plugin, delete_plugin = $delete_plugin WHERE id=$id";
		if(!$rs = mysql_query($sql)){
			echo "An error occured while attempting to update the role. <br />".mysql_error();
			exit;
		} 
		$header="Location: index.php?a=75&r=2";
		header($header);
    break;
    default:
	?>
	Erm... You supposed to be here now?
	<?php
	exit;
}
?>