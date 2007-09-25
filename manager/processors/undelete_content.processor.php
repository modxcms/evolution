<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_document')) {	
	$e->setError(3);
	$e->dumpError();	
}

$id=$_REQUEST['id'];

// check permissions on the document
include_once "./processors/user_documents_permissions.class.php";
$udperms = new udperms();
$udperms->user = $modx->getLoginUserID();
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];

if(!$udperms->checkPermissions()) {
	include "header.inc.php";
	?><br /><br /><div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
	<p><?php echo $_lang['access_permission_denied']; ?></p>
	<?php
	include("footer.inc.php");
	exit;	
}

// get the timestamp on which the document was deleted.
$sql = "SELECT deletedon FROM $dbase.`".$table_prefix."site_content` WHERE $dbase.`".$table_prefix."site_content`.id=".$id." AND deleted=1;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit!=1) {
	echo "Couldn't find document to determine it's date of deletion!";
	exit;
} else {
	$row=mysql_fetch_assoc($rs);
	$deltime = $row['deletedon'];
}

$children = array();

function getChildren($parent) {
	
	global $dbase;
	global $table_prefix;
	global $children;
	global $deltime;
	
	$db->debug = true;
	
	$sql = "SELECT id FROM $dbase.`".$table_prefix."site_content` WHERE $dbase.`".$table_prefix."site_content`.parent=".$parent." AND deleted=1 AND deletedon=$deltime;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>0) {
		// the document has children documents, we'll need to delete those too
		for($i=0;$i<$limit;$i++) {
		$row=mysql_fetch_assoc($rs);
			$children[] = $row['id'];
			getChildren($row['id']);
			//echo "Found childNode of parentNode $parent: ".$row['id']."<br />";
		}
	}
}

getChildren($id);

if(count($children)>0) {
	$docs_to_undelete = implode(" ,", $children);
	$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET deleted=0, deletedby=0, deletedon=0 WHERE id IN($docs_to_undelete);";
	$rs = @mysql_query($sql);
	if(!$rs) {
		echo "Something went wrong while trying to set the document's children to undeleted status...";
		exit;
	}
}
//'undelete' the document.
$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET deleted=0, deletedby=0, deletedon=0 WHERE id=$id;";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to set the document to undeleted status...";
	exit;
} else {
	// empty cache
	include_once "cache_sync.class.processor.php";
	$sync = new synccache();
	$sync->setCachepath("../assets/cache/");
	$sync->setReport(false);
	$sync->emptyCache(); // first empty the cache		
	// finished emptying cache - redirect
	$header="Location: index.php?r=1&a=7";
	header($header);
}
?>
