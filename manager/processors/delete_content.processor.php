<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if(!$modx->hasPermission('delete_document')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php
// check the document doesn't have any children
$id=intval($_GET['id']);
$deltime = time();
$children = array();

// check permissions on the document
include_once "./processors/user_documents_permissions.class.php";
$udperms = new udperms();
$udperms->user = $modx->getLoginUserID();
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];

if(!$udperms->checkPermissions()) {
	include "header.inc.php";
	?><div class="sectionHeader"><?php echo $_lang['access_permissions']; ?></div>
	<div class="sectionBody">
	<p><?php echo $_lang['access_permission_denied']; ?></p>
	<?php
	include("footer.inc.php");
	exit;
}

function getChildren($parent) {

	global $dbase;
	global $table_prefix;
	global $children;
	global $site_start;
	global $site_unavailable_page;

	//$db->debug = true;

	$sql = "SELECT id FROM $dbase.`".$table_prefix."site_content` WHERE $dbase.`".$table_prefix."site_content`.parent=".$parent." AND deleted=0;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>0) {
		// the document has children documents, we'll need to delete those too
		for($i=0;$i<$limit;$i++) {
		$row=mysql_fetch_assoc($rs);
			if($row['id']==$site_start) {
				echo "The document you are trying to delete is a folder containing document ".$row['id'].". This document is registered as the 'Site start' document, and cannot be deleted. Please assign another document as your 'Site start' document and try again.";
				exit;
			}
			if($row['id']==$site_unavailable_page) {
				echo "The document you are trying to delete is a folder containing document ".$row['id'].". This document is registered as the 'Site unavailable page' document, and cannot be deleted. Please assign another document as your 'Site unavailable page' document and try again.";
				exit;
			}
			$children[] = $row['id'];
			getChildren($row['id']);
			//echo "Found childNode of parentNode $parent: ".$row['id']."<br />";
		}
	}
}

getChildren($id);

// invoke OnBeforeDocFormDelete event
$modx->invokeEvent("OnBeforeDocFormDelete",
						array(
							"id"=>$id,
							"children"=>$children
						));

if(count($children)>0) {
	$docs_to_delete = implode(" ,", $children);
	$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET deleted=1, deletedby=".$modx->getLoginUserID().", deletedon=$deltime WHERE id IN($docs_to_delete);";
	$rs = @mysql_query($sql);
	if(!$rs) {
		echo "Something went wrong while trying to set the document's children to deleted status...";
		exit;
	}
}

if($site_start==$id){
	echo "Document is 'Site start' and cannot be deleted!";
	exit;
}

if($site_unavailable_page==$id){
	echo "Document is used as the 'Site unavailable page' and cannot be deleted!";
	exit;
}

//ok, 'delete' the document.
$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET deleted=1, deletedby=".$modx->getLoginUserID().", deletedon=$deltime WHERE id=$id;";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to set the document to deleted status...";
	exit;
} else {
	// invoke OnDocFormDelete event
	$modx->invokeEvent("OnDocFormDelete",
							array(
								"id"=>$id,
								"children"=>$children
							));

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