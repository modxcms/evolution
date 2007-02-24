<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_document')||!$modx->hasPermission('publish_document')) {
	$e->setError(3);
	$e->dumpError();	
}

$id = $_REQUEST['id'];

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

// update the document
$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET published=1, pub_date=0, unpub_date=0, editedby=".$modx->getLoginUserID().", editedon=".time().", publishedby=".$modx->getLoginUserID().", publishedon=".time()." WHERE id=$id;";

$rs = mysql_query($sql);
if(!$rs){
	echo "An error occured while attempting to publish the document.";
}

// invoke OnDocPublished  event
$modx->invokeEvent("OnDocPublished",array("docid"=>$id));	

include_once "cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache		

$header="Location: index.php?r=1&id=$id&a=7";
header($header);

?>
