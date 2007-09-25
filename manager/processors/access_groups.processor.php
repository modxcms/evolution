<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('access_permissions')) {	
	$e->setError(3);
	$e->dumpError();	
}

// access group processor.
// figure out what the user wants to do...

$updategroupaccess = false;
$operation = $_REQUEST['operation'];

switch ($operation) {
	case "add_user_group" :
		$newgroup = $_REQUEST['newusergroup'];
		if(empty($newgroup)) {
			echo "no group name specified";
			exit;
		} else {
			$sql = "INSERT INTO $dbase.`".$table_prefix."membergroup_names` (name) VALUES('".$newgroup."')";
			if(!$rs = mysql_query($sql)) {
				echo "Failed to insert new group. Possible duplicate group name?";
				exit;
			}

			// get new id
			$id = mysql_insert_id();
			
			// invoke OnManagerCreateGroup event
			$modx->invokeEvent("OnManagerCreateGroup",
								array(
									"groupid"	=> $id,
									"groupname"	=> $newgroup
								));
		}
	break;
	case "add_document_group" :
		$newgroup = $_REQUEST['newdocgroup'];
		if(empty($newgroup)) {
			echo "no group name specified";
			exit;
		} else {
			$sql = "INSERT INTO $dbase.`".$table_prefix."documentgroup_names` (name) VALUES('".$newgroup."')";
			if(!$rs = mysql_query($sql)) {
				echo "Failed to insert new group. Possible duplicate group name?";
				exit;
			}
			
			// get new id
			$id = mysql_insert_id();

			// invoke OnCreateDocGroup event
			$modx->invokeEvent("OnCreateDocGroup",
								array(
									"groupid"	=> $id,
									"groupname"	=> $newgroup
								));			
		}
	break;
	case "delete_user_group" :
		$updategroupaccess = true;
		$usergroup = $_REQUEST['usergroup'];
		if(empty($usergroup)) {
			echo "No user group name specified for deletion";
			exit;
		} else {
			$sql = "DELETE FROM $dbase.`".$table_prefix."membergroup_names` WHERE id='".$usergroup."'";
			if(!$rs = mysql_query($sql)) {
				echo "Unable to delete group. SQL failed.";
				exit;
			}
			$sql = "DELETE FROM $dbase.`".$table_prefix."membergroup_access` WHERE membergroup='".$usergroup."'";
			if(!$rs = mysql_query($sql)) {
				echo "Unable to delete group from access table. SQL failed.";
				exit;
			}
			$sql = "DELETE FROM $dbase.`".$table_prefix."member_groups` WHERE user_group='".$usergroup."'";
			if(!$rs = mysql_query($sql)) {
				echo "Unable to delete user-group links. SQL failed.";
				exit;
			}
		}
	break;	
	case "delete_document_group" :
		$group = $_REQUEST['documentgroup'];
		if(empty($group)) {
			echo "No user group name specified for deletion";
			exit;
		} else {
			$sql = "DELETE FROM $dbase.`".$table_prefix."documentgroup_names` WHERE id='".$group."'";
			if(!$rs = mysql_query($sql)) {
				echo "Unable to delete group. SQL failed.";
				exit;
			}
			$sql = "DELETE FROM $dbase.`".$table_prefix."membergroup_access` WHERE documentgroup='".$group."'";
			if(!$rs = mysql_query($sql)) {
				echo "Unable to delete group from access table. SQL failed.";
				exit;
			}
			$sql = "DELETE FROM $dbase.`".$table_prefix."document_groups` WHERE document_group='".$group."'";
			if(!$rs = mysql_query($sql)) {
				echo "Unable to delete document-group links. SQL failed.";
				exit;
			}
		}
	break;
	case "rename_user_group" :
		$newgroupname = $_REQUEST['newgroupname'];
		if(empty($newgroupname)) {
			echo "no group name specified";
			exit;
		}
		$groupid = $_REQUEST['groupid'];
		if(empty($groupid)) {
			echo "No group id specified";
			exit;
		}
		$sql = "UPDATE $dbase.`".$table_prefix."membergroup_names` SET name='".$newgroupname."' WHERE id='".$groupid."' LIMIT 1";
		if(!$rs = mysql_query($sql)) {
			echo "Failed to update group name. Possible duplicate group name?";
			exit;
		}
	break;	
	case "rename_document_group" :
		$newgroupname = $_REQUEST['newgroupname'];
		if(empty($newgroupname)) {
			echo "no group name specified";
			exit;
		}
		$groupid = $_REQUEST['groupid'];
		if(empty($groupid)) {
			echo "No group id specified";
			exit;
		}
		$sql = "UPDATE $dbase.`".$table_prefix."documentgroup_names` SET name='".$newgroupname."' WHERE id='".$groupid."' LIMIT 1";
		if(!$rs = mysql_query($sql)) {
			echo "Failed to update group name. Possible duplicate group name?";
			exit;
		}
	break;	
	case "add_document_group_to_user_group" :
		$updategroupaccess = true;
		$usergroup = $_REQUEST['usergroup'];
		$docgroup = $_REQUEST['docgroup'];
		$sql = "SELECT * FROM $dbase.`".$table_prefix."membergroup_access` WHERE membergroup='$usergroup' AND documentgroup='$docgroup'";		
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);			
		if($limit<=0) {
			$sql = "INSERT INTO $dbase.`".$table_prefix."membergroup_access` (membergroup, documentgroup) VALUES('".$usergroup."', '".$docgroup."')";
			if(!$rs = mysql_query($sql)) {
				echo "Failed to link document group to user group";
				exit;
			}
		} else {
			//alert user that coupling already exists?
		}
	break;
	case "remove_document_group_from_user_group" :
		$updategroupaccess = true;
		$coupling = $_REQUEST['coupling'];
		$sql = "DELETE FROM $dbase.`".$table_prefix."membergroup_access` WHERE id='".$coupling."'";
		if(!$rs = mysql_query($sql)) {
			echo "Failed to remove document group from user group";
			exit;
		}
	break;
	default :
		echo "No operation set in request.";
		exit;
}

// secure manager documents - flag as private 
if($updategroupaccess==true){
	include $base_path."manager/includes/secure_mgr_documents.inc.php";
	secureMgrDocument();
}

$header = "Location: index.php?a=40";
header($header);
?>
