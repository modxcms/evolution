<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['delete_user']!=1 && $_REQUEST['a']==33) {	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

$id=intval($_GET['id']);

// delete the user, but first check if we are deleting our own record
if($id==$_SESSION['internalKey']) {
	echo "You can't delete yourself!";
	exit;
}

// get user name
$sql = "SELECT * FROM $dbase.".$table_prefix."manager_users WHERE $dbase.".$table_prefix."manager_users.id='".$id."' LIMIT 1;";
$rs = mysql_query($sql);
if($rs) {
	$row = mysql_fetch_row($rs);
	$username = $row['username'];
}

// invoke OnBeforeUserFormDelete event
$modx->invokeEvent("OnBeforeUserFormDelete",
						array(
							"id"	=> $id
						));

//ok, delete the user.
$sql = "DELETE FROM $dbase.".$table_prefix."manager_users WHERE $dbase.".$table_prefix."manager_users.id=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the user...";
	exit;
} 

$sql = "DELETE FROM $dbase.".$table_prefix."member_groups WHERE $dbase.".$table_prefix."member_groups.member=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the user's access permissions...";
	exit;
} 

// delete the attributes
$sql = "DELETE FROM $dbase.".$table_prefix."user_attributes WHERE $dbase.".$table_prefix."user_attributes.internalKey=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the user attributes...";
	exit;
} else {		
	// invoke OnManagerDeleteUser event
	$modx->invokeEvent("OnManagerDeleteUser",
						array(
							"userid"		=> $id,
							"username"		=> $username
						));

	// invoke OnUserFormDelete event
	$modx->invokeEvent("OnUserFormDelete",
						array(
							"id"	=> $id
						));
						
	$header="Location: index.php?a=75";
	header($header);
}

?>