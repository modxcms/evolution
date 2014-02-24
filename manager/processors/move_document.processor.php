<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_document')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// ok, two things to check.
// first, document cannot be moved to itself
// second, new parent must be a folder. If not, set it to folder.
if($_REQUEST['id']==$_REQUEST['new_parent']) {
		$modx->webAlertAndQuit($_lang["error_movedocument1"]);
}
if($_REQUEST['id']=="") {
		$modx->webAlertAndQuit($_lang["error_movedocument2"]);
}
if($_REQUEST['new_parent']=="") {
		$modx->webAlertAndQuit($_lang["error_movedocument2"]);
}

$rs = $modx->db->select('parent', $modx->getFullTableName('site_content'), "id='{$_REQUEST['id']}'");

$oldparent = $modx->db->getValue($rs);
$newParentID = $_REQUEST['new_parent'];

// check user has permission to move document to chosen location

if ($use_udperms == 1) {
if ($oldparent != $newParentID) {
		include_once MODX_MANAGER_PATH . "processors/user_documents_permissions.class.php";
		$udperms = new udperms();
		$udperms->user = $modx->getLoginUserID();
		$udperms->document = $newParentID;
		$udperms->role = $_SESSION['mgrRole'];

		 if (!$udperms->checkPermissions()) {
			$modx->webAlertAndQuit($_lang["access_permission_parent_denied"]);
		 }
	}
}

function allChildren($currDocID) {
	global $modx;
	$children= array();
	$rs = $modx->db->select('id', $modx->getFullTableName('site_content'), "parent = '{$currDocID}'");
			while ($child= $modx->db->getRow($rs)) {
				$children[]= $child['id'];
				$nextgen= array();
				$nextgen= allChildren($child['id']);
				$children= array_merge($children, $nextgen);
			}
	return $children;
}

$children= allChildren($_REQUEST['id']);

if (!array_search($newParentID, $children)) {

	$modx->db->update(
		array(
			'isfolder' => 1,
		), $modx->getFullTableName('site_content'), "id='{$_REQUEST['new_parent']}'");

	$modx->db->update(
		array(
			'parent'   => $_REQUEST['new_parent'],
			'editedby' => $modx->getLoginUserID(),
			'editedon' => time(),
		), $modx->getFullTableName('site_content'), "id='{$_REQUEST['id']}'");

	// finished moving the document, now check to see if the old_parent should no longer be a folder.
	$rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_content'), "parent='{$oldparent}'");
	$limit = $modx->db->getValue($rs);

	if(!$limit>0) {
		$modx->db->update(
			array(
				'isfolder' => 0,
			), $modx->getFullTableName('site_content'), "id='{$oldparent}'");
	}

	// Set the item name for logger
	$pagetitle = $modx->db->getValue($modx->db->select('pagetitle', $modx->getFullTableName('site_content'), "id='{$id}'"));
	$_SESSION['itemname'] = $pagetitle;

	// empty cache & sync site
	$modx->clearCache('full');

	$header="Location: index.php?r=1&id=$id&a=7";
	header($header);
} else {
	$modx->webAlertAndQuit("You cannot move a document to a child document!");
}
?>