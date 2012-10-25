<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('delete_web_user')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php

$id=intval($_GET['id']);

// get user name
$sql = "SELECT * FROM $dbase.`".$table_prefix."web_users` WHERE $dbase.`".$table_prefix."web_users`.id='".$id."' LIMIT 1;";
$rs = mysql_query($sql);
if($rs) {
	$row = mysql_fetch_assoc($rs);
	$username = $row['username'];
}


// invoke OnBeforeWUsrFormDelete event
$modx->invokeEvent("OnBeforeWUsrFormDelete",
					array(
						"id"	=> $id
					));

// delete the user.
$sql = "DELETE FROM $dbase.`".$table_prefix."web_users` WHERE $dbase.`".$table_prefix."web_users`.id=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the web user...";
	exit;
}
// delete user groups
$sql = "DELETE FROM $dbase.`".$table_prefix."web_groups` WHERE $dbase.`".$table_prefix."web_groups`.webuser=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the web user's access permissions...";
	exit;
}
// delete the attributes
$sql = "DELETE FROM $dbase.`".$table_prefix."web_user_attributes` WHERE $dbase.`".$table_prefix."web_user_attributes`.internalKey=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the web user attributes...";
	exit;
} else {
	// invoke OnWebDeleteUser event
	$modx->invokeEvent("OnWebDeleteUser",
						array(
							"userid"		=> $id,
							"username"		=> $username
						));

	// invoke OnWUsrFormDelete event
	$modx->invokeEvent("OnWUsrFormDelete",
						array(
							"id"	=> $id
						));

	$header="Location: index.php?a=99";
	header($header);
}
?>
