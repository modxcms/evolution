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

$sql = "SELECT parent FROM $dbase.`".$table_prefix."site_content` WHERE id=".$_REQUEST['id'].";";
$rs = $modx->db->query($sql);
if(!$rs){
	$modx->webAlertAndQuit("An error occured while attempting to find the document's current parent.");
}

$row = $modx->db->getRow($rs);
$oldparent = $row['parent'];
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
	$sql = "SELECT id FROM ".$modx->getFullTableName('site_content')." WHERE parent = $currDocID;";
	if(!$rs = $modx->db->query($sql)) {
		$modx->webAlertAndQuit("An error occured while attempting to find all of the document's children.");
	} else {
		if ($numChildren= $modx->db->getRecordCount($rs)) {
			while ($child= $modx->db->getRow($rs)) {
				$children[]= $child['id'];
				$nextgen= array();
				$nextgen= allChildren($child['id']);
				$children= array_merge($children, $nextgen);
			}
		}
	}
	return $children;
}

$children= allChildren($_REQUEST['id']);

if (!array_search($newParentID, $children)) {

	$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET isfolder=1 WHERE id=".$_REQUEST['new_parent'].";";
	$rs = $modx->db->query($sql);
	if(!$rs){
		$modx->webAlertAndQuit("An error occured while attempting to change the new parent to a folder.");
	}

	$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET parent=".$_REQUEST['new_parent'].", editedby=".$modx->getLoginUserID().", editedon=".time()." WHERE id=".$_REQUEST['id'].";";
	$rs = $modx->db->query($sql);
	if(!$rs){
		$modx->webAlertAndQuit("An error occured while attempting to move the document to the new parent.");
	}

	// finished moving the document, now check to see if the old_parent should no longer be a folder.
	$sql = "SELECT count(*) FROM $dbase.`".$table_prefix."site_content` WHERE parent=$oldparent;";
	$rs = $modx->db->query($sql);
	if(!$rs){
		$modx->webAlertAndQuit("An error occured while attempting to find the old parents' children.");
	}
	$row = $modx->db->getRow($rs);
	$limit = $row['count(*)'];

	if(!$limit>0) {
		$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET isfolder=0 WHERE id=$oldparent;";
		$rs = $modx->db->query($sql);
		if(!$rs){
			$modx->webAlertAndQuit("An error occured while attempting to change the old parent to a regular document.");
		}
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