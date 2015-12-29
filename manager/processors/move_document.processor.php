<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_document')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$newParentID = isset($_REQUEST['new_parent']) ? (int)$_REQUEST['new_parent'] : 0;
$documentID = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

// ok, two things to check.
// first, document cannot be moved to itself
// second, new parent must be a folder. If not, set it to folder.
if($documentID==$newParentID) $modx->webAlertAndQuit($_lang["error_movedocument1"]);
if($documentID <= 0) $modx->webAlertAndQuit($_lang["error_movedocument2"]);
if($newParentID < 0) $modx->webAlertAndQuit($_lang["error_movedocument2"]);

$rs = $modx->db->select('parent', $modx->getFullTableName('site_content'), "id='{$documentID}'");
$oldparent = $modx->db->getValue($rs);

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

$evtOut = $modx->invokeEvent("onBeforeMoveDocument", array (
	"id_document" => $documentID,
	"old_parent" => $oldparent,
	"new_parent" => $newParentID
));
if (is_array($evtOut) && count($evtOut) > 0){
	$newParent = array_pop($evtOut);
	if($newParent == $oldparent) {
		$modx->webAlertAndQuit($_lang["error_movedocument2"]);
	}else{
		$newParentID = $newParent;
	}
}

$children = allChildren($documentID);
if (!array_search($newParentID, $children)) {
	$modx->db->update(array(
		'isfolder' => 1,
	), $modx->getFullTableName('site_content'), "id='{$newParentID}'");

	$modx->db->update(array(
		'parent'   => $newParentID,
		'editedby' => $modx->getLoginUserID(),
		'editedon' => time(),
	), $modx->getFullTableName('site_content'), "id='{$documentID}'");

	// finished moving the document, now check to see if the old_parent should no longer be a folder.
	$rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_content'), "parent='{$oldparent}'");
	$limit = $modx->db->getValue($rs);

	if(!$limit>0) {
		$modx->db->update(array(
			'isfolder' => 0,
		), $modx->getFullTableName('site_content'), "id='{$oldparent}'");
	}
	// Set the item name for logger
	$pagetitle = $modx->db->getValue($modx->db->select('pagetitle', $modx->getFullTableName('site_content'), "id='{$documentID}'"));
	$_SESSION['itemname'] = $pagetitle;

	$modx->invokeEvent("onAfterMoveDocument", array (
		"id_document" => $documentID,
		"old_parent" => $oldparent,
		"new_parent" => $newParentID
	));

	// empty cache & sync site
	$modx->clearCache('full');

	$header="Location: index.php?r=1&id={$documentID}&a=7";
	header($header);
} else {
	$modx->webAlertAndQuit("You cannot move a document to a child document!");
}