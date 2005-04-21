<?php 
if(IN_ETOMITE_SYSTEM!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
if($_SESSION['permissions']['save_document']!=1 && $_REQUEST['a']==94) {	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

// check the document doesn't have any children
$id=$_GET['id'];
$children = array();

// check permissions on the document
include_once "./processors/user_documents_permissions.class.php";
$udperms = new udperms();
$udperms->user = $_SESSION['internalKey'];
$udperms->document = $id;
$udperms->role = $_SESSION['role'];

if(!$udperms->checkPermissions()) {
	include "header.inc.php";
	?><br /><br /><div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
	<p><?php echo $_lang['access_permission_denied']; ?></p>
	<?php
	include("footer.inc.php");
	exit;	
}

// get document's parent id
$sql = "SELECT parent FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.id=$id;";
$rs = mysql_query($sql);
if(!rs){
	echo "A database error occured while trying to load document: <br /><br />".mysql_error();
	exit;
}
else {
	$row=mysql_fetch_assoc($rs);
	$parent = $row['parent'];
}

// get document's children
$children = getChildren($id);

cloneDocument($parent,$id,$children);

function cloneDocument($parent,$docid,$children){
	
	global $dbase, $table_prefix;
	
	$myChildren = array();
	// clone document
	$sql = "INSERT INTO $dbase.".$table_prefix."site_content (type, contentType, pagetitle, longtitle, description, alias, published, pub_date, unpub_date, parent, isfolder, introtext, content, richtext, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, deleted, deletedon, deletedby) 
			SELECT type, contentType, pagetitle, longtitle, description, alias, published, pub_date, unpub_date, '$parent' as 'parent', isfolder, introtext, content, richtext, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, deleted, deletedon, deletedby 
			FROM $dbase.".$table_prefix."site_content WHERE id=$docid;";
	$rs = mysql_query($sql);
	if($rs) $parent = mysql_insert_id(); // get new parent id
	else {
		echo "A database error occured while trying to clone document: <br /><br />".mysql_error();
		exit;
	}
	// clone document's children.
	if(is_array($children)) {
		foreach($children as $id => $child){
			if (is_array($child)) cloneDocument($parent,$id,$child); // clone my child with grandchildren
			else $myChildren[] = $id;  // used to clone my child without grandchildren
		}
		if(count($myChildren)>0) {
			$docs_to_cloned = implode(" ,", $myChildren);
			$sql = "INSERT INTO $dbase.".$table_prefix."site_content (type, contentType, pagetitle, longtitle, description, alias, published, pub_date, unpub_date, parent, isfolder, introtext, content, richtext, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, deleted, deletedon, deletedby) 
					SELECT type, contentType, pagetitle, longtitle, description, alias, published, pub_date, unpub_date, '$parent' as 'parent', isfolder, introtext, content, richtext, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, deleted, deletedon, deletedby 
					FROM $dbase.".$table_prefix."site_content WHERE id IN($docs_to_cloned);";
			$rs = @mysql_query($sql);
			if(!$rs) {
				echo "A database error occured while trying to clone document's children:<br /><br />".mysql_error();
				exit;
			}
		}
	}
}

// finish cloning - redirect
$header="Location: index.php?r=1&a=3&id=$id";
header($header);

function getChildren($parent) {

	global $dbase;
	global $table_prefix;
	global $site_start;

	
	//$db->debug = true;
	
	$sql = "SELECT id FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.parent=".$parent." AND deleted=0;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>0) {
		$children = array();
		// the document has children documents, we'll need to clone those too
		for($i=0;$i<$limit;$i++) {
			$row=mysql_fetch_assoc($rs);
			$c = getChildren($row['id']);
			$children[$row['id']] = ($c) ?  $c:$row['id'];
		}
	}
	return $children;
}

?>