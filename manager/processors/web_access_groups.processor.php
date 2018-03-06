<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('web_access_permissions')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// web access group processor.
// figure out what the user wants to do...

// Get table names (alphabetical)
$tbl_document_groups     = $modx->getFullTableName('document_groups');
$tbl_documentgroup_names = $modx->getFullTableName('documentgroup_names');
$tbl_web_groups          = $modx->getFullTableName('web_groups');
$tbl_webgroup_access     = $modx->getFullTableName('webgroup_access');
$tbl_webgroup_names      = $modx->getFullTableName('webgroup_names');

$updategroupaccess = false;
$operation = $_REQUEST['operation'];

switch ($operation) {
	case "add_user_group" :
		$newgroup = $_REQUEST['newusergroup'];
		if(empty($newgroup)) {
			$modx->webAlertAndQuit("No group name specified.");
		} else {
			$id = $modx->db->insert(array('name'=>$modx->db->escape($newgroup)), $tbl_webgroup_names);

			// invoke OnWebCreateGroup event
			$modx->invokeEvent('OnWebCreateGroup', array(
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
			$id = $modx->db->insert(array('name'=>$modx->db->escape($newgroup)), $tbl_documentgroup_names);

			// invoke OnCreateDocGroup event
			$modx->invokeEvent('OnCreateDocGroup', array(
				'groupid'   => $id,
				'groupname' => $newgroup,
			));
		}
	break;
	case "delete_user_group" :
		$updategroupaccess = true;
		$usergroup = (int)$_REQUEST['usergroup'];
		if(empty($usergroup)) {
			$modx->webAlertAndQuit("No user group id specified for deletion.");
		} else {
			$modx->db->delete($tbl_webgroup_names, "id='{$usergroup}'");

			$modx->db->delete($tbl_webgroup_access, "webgroup='{$usergroup}'");

			$modx->db->delete($tbl_web_groups, "webuser='{$usergroup}'");
		}
	break;
	case "delete_document_group" :
		$group = (int)$_REQUEST['documentgroup'];
		if(empty($group)) {
			$modx->webAlertAndQuit("No document group id specified for deletion.");
		} else {
			$modx->db->delete($tbl_documentgroup_names, "id='{$group}'");

			$modx->db->delete($tbl_webgroup_access, "documentgroup='{$group}'");

			$modx->db->delete($tbl_document_groups, "document_group='{$group}'");
		}
	break;
	case "rename_user_group" :
		$newgroupname = $_REQUEST['newgroupname'];
		if(empty($newgroupname)) {
			$modx->webAlertAndQuit("No group name specified.");
		}
		$groupid = (int)$_REQUEST['groupid'];
		if(empty($groupid)) {
			$modx->webAlertAndQuit("No user group id specified for rename.");
		}
		$modx->db->update(array('name' => $modx->db->escape($newgroupname)), $tbl_webgroup_names, "id='{$groupid}'");
	break;
	case "rename_document_group" :
		$newgroupname = $_REQUEST['newgroupname'];
		if(empty($newgroupname)) {
			$modx->webAlertAndQuit("No group name specified.");
		}
		$groupid = (int)$_REQUEST['groupid'];
		if(empty($groupid)) {
			$modx->webAlertAndQuit("No document group id specified for rename.");
		}
		$modx->db->update(array('name' => $modx->db->escape($newgroupname)), $tbl_documentgroup_names, "id='{$groupid}'");
	break;
	case "add_document_group_to_user_group" :
		$updategroupaccess = true;
		$usergroup = (int)$_REQUEST['usergroup'];
		$docgroup = (int)$_REQUEST['docgroup'];
		$rs = $modx->db->select('COUNT(*)', $tbl_webgroup_access, "webgroup='{$usergroup}' AND documentgroup='{$docgroup}'");
		$limit = $modx->db->getValue($rs);
		if($limit<=0) {
			$modx->db->insert(array('webgroup'=>$usergroup, 'documentgroup'=>$docgroup), $tbl_webgroup_access);
		} else {
			//alert user that coupling already exists?
		}
	break;
	case "remove_document_group_from_user_group" :
		$updategroupaccess = true;
		$coupling = (int)$_REQUEST['coupling'];
		$modx->db->delete($tbl_webgroup_access, "id='{$coupling}'");
	break;
	default :
		$modx->webAlertAndQuit("No operation set in request.");
}

// secure web documents - flag as private
if($updategroupaccess==true){
	include MODX_MANAGER_PATH."includes/secure_web_documents.inc.php";
	secureWebDocument();

	// Update the private group column
	$modx->db->update(
		'dgn.private_webgroup = (wga.webgroup IS NOT NULL)',
		"{$tbl_documentgroup_names} AS dgn LEFT JOIN {$tbl_webgroup_access} AS wga ON wga.documentgroup = dgn.id");
}

$header = "Location: index.php?a=91";
header($header);
