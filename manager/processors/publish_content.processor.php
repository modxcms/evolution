<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_document')||!$modx->hasPermission('publish_document')) {
	$e->setError(3);
	$e->dumpError();	
}

$id = $_REQUEST['id'];


/************webber ********/
$content=$modx->db->getRow($modx->db->select('parent, pagetitle', $modx->getFullTableName('site_content'), "id='{$id}'"));
$pid=($content['parent']==0?$id:$content['parent']);

/************** webber *************/
$sd=isset($_REQUEST['dir'])?'&dir='.$_REQUEST['dir']:'&dir=DESC';
$sb=isset($_REQUEST['sort'])?'&sort='.$_REQUEST['sort']:'&sort=createdon';
$pg=isset($_REQUEST['page'])?'&page='.(int)$_REQUEST['page']:'';
$add_path=$sd.$sb.$pg;

/***********************************/



// check permissions on the document
include_once MODX_MANAGER_PATH . "processors/user_documents_permissions.class.php";
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

// update the document
$sql = "UPDATE $dbase.`".$table_prefix."site_content` SET published=1, pub_date=0, unpub_date=0, editedby=".$modx->getLoginUserID().", editedon=".time().", publishedby=".$modx->getLoginUserID().", publishedon=".time()." WHERE id=$id;";

$rs = $modx->db->query($sql);
if(!$rs){
	echo "An error occured while attempting to publish the document.";
}

// invoke OnDocPublished  event
$modx->invokeEvent("OnDocPublished",array("docid"=>$id));	

// Set the item name for logger
$_SESSION['itemname'] = $content['pagetitle'];

$modx->clearCache('full');

//$header="Location: index.php?r=1&id=$id&a=7";

// webber
$header="Location: index.php?r=1&id=$pid&a=7&dv=1".$add_path;

header($header);
?>