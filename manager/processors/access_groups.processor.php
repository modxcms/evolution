<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('access_permissions')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// access group processor.
// figure out what the user wants to do...

// Get table names (alphabetical)
$tbl_document_groups     = $modx->getFullTableName('document_groups');
$tbl_documentgroup_names = $modx->getFullTableName('documentgroup_names');
$tbl_member_groups       = $modx->getFullTableName('member_groups');
$tbl_membergroup_access  = $modx->getFullTableName('membergroup_access');
$tbl_membergroup_names   = $modx->getFullTableName('membergroup_names');

$updategroupaccess = false;
$operation = $_REQUEST['operation'];

switch ($operation) {
	case "add_user_group" :
		$newgroup = $_REQUEST['newusergroup'];
		if(empty($newgroup)) {
			$modx->webAlertAndQuit("No group name specified.");
		} else {
			$sql = 'INSERT INTO '.$tbl_membergroup_names.' (name) VALUES(\''.$modx->db->escape($newgroup).'\')';
			$modx->db->query($sql);

			// get new id
			$id = $modx->db->getInsertId();

			// invoke OnManagerCreateGroup event
			$modx->invokeEvent('OnManagerCreateGroup', array(
				'groupid'   => $id,
				'groupname' => $newgroup,
			));
		}
	break;
	case "add_document_group" :
		$newgroup = $_REQUEST['newdocgroup'];
		if(empty($newgroup)) {
			$modx->webAlertAndQuit("No group name specified.");
		} else {
			$sql = 'INSERT INTO '.$tbl_documentgroup_names.' (name) VALUES(\''.$modx->db->escape($newgroup).'\')';
			$modx->db->query($sql);

			// get new id
			$id = $modx->db->getInsertId();

			// invoke OnCreateDocGroup event
			$modx->invokeEvent('OnCreateDocGroup', array(
				'groupid'   => $id,
				'groupname' => $newgroup,
			));
		}
	break;
	case "delete_user_group" :
		$updategroupaccess = true;
		$usergroup = intval($_REQUEST['usergroup']);
		if(empty($usergroup)) {
			$modx->webAlertAndQuit("No user group id specified for deletion.");
		} else {
			$sql = 'DELETE FROM '.$tbl_membergroup_names.' WHERE id='.$usergroup;
			$modx->db->query($sql);

			$sql = 'DELETE FROM '.$tbl_membergroup_access.' WHERE membergroup='.$usergroup;
			$modx->db->query($sql);

			$sql = 'DELETE FROM '.$tbl_member_groups.' WHERE user_group='.$usergroup;
			$modx->db->query($sql);

		}
	break;
	case "delete_document_group" :
		$group = intval($_REQUEST['documentgroup']);
		if(empty($group)) {
			$modx->webAlertAndQuit("No document group id specified for deletion.");
		} else {
			$sql = 'DELETE FROM '.$tbl_documentgroup_names.' WHERE id='.$group;
			$modx->db->query($sql);

			$sql = 'DELETE FROM '.$tbl_membergroup_access.' WHERE documentgroup='.$group;
			$modx->db->query($sql);

			$sql = 'DELETE FROM '.$tbl_document_groups.' WHERE document_group='.$group;
			$modx->db->query($sql);
		}
	break;
	case "rename_user_group" :
		$newgroupname = $modx->db->escape($_REQUEST['newgroupname']);
		if(empty($newgroupname)) {
			$modx->webAlertAndQuit("No group name specified.");
		}
		$groupid = intval($_REQUEST['groupid']);
		if(empty($groupid)) {
			$modx->webAlertAndQuit("No group id specified for rename.");
		}
		$sql = 'UPDATE '.$tbl_membergroup_names.' SET name=\''.$newgroupname.'\' WHERE id='.$groupid.' LIMIT 1';
		$modx->db->query($sql);
	break;
	case "rename_document_group" :
		$newgroupname = $modx->db->escape($_REQUEST['newgroupname']);
		if(empty($newgroupname)) {
			$modx->webAlertAndQuit("No group name specified.");
		}
		$groupid = intval($_REQUEST['groupid']);
		if(empty($groupid)) {
			$modx->webAlertAndQuit("No group id specified for rename.");
		}
		$sql = 'UPDATE '.$tbl_documentgroup_names.' SET name=\''.$newgroupname.'\' WHERE id='.$groupid.' LIMIT 1';
		$modx->db->query($sql);
	break;
	case "add_document_group_to_user_group" :
		$updategroupaccess = true;
		$usergroup = intval($_REQUEST['usergroup']);
		$docgroup = intval($_REQUEST['docgroup']);
		$sql = 'SELECT count(*) FROM '.$tbl_membergroup_access.' WHERE membergroup='.$usergroup.' AND documentgroup='.$docgroup;
		$limit = $modx->db->getValue($sql);
		if($limit<=0) {
			$sql = 'INSERT INTO '.$tbl_membergroup_access.' (membergroup, documentgroup) VALUES('.$usergroup.', '.$docgroup.')';
			$modx->db->query($sql);
		} else {
			//alert user that coupling already exists?
		}
	break;
	case "remove_document_group_from_user_group" :
		$updategroupaccess = true;
		$coupling = intval($_REQUEST['coupling']);
		$sql = 'DELETE FROM '.$tbl_membergroup_access.' WHERE id='.$coupling;
		$modx->db->query($sql);
	break;
	default :
		$modx->webAlertAndQuit("No operation set in request.");
}

// secure manager documents - flag as private
if($updategroupaccess==true){
	include MODX_MANAGER_PATH."includes/secure_mgr_documents.inc.php";
	secureMgrDocument();

	// Update the private group column
	$sql = 'UPDATE '.$tbl_documentgroup_names.' AS dgn '.
	       'LEFT JOIN '.$tbl_membergroup_access.' AS mga ON mga.documentgroup = dgn.id '.
	       'SET dgn.private_memgroup = (mga.membergroup IS NOT NULL)';
	$modx->db->query($sql);
}

$header = "Location: index.php?a=40";
header($header);
?>