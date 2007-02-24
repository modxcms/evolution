<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_role')) {	
	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

$id=$_GET['id'];

if($id==1){
	echo "The role you are trying to delete is the admin role. This role cannot be deleted!";
	exit;
}


$sql = "SELECT count(*) FROM $dbase.`".$table_prefix."user_attributes` WHERE $dbase.`".$table_prefix."user_attributes`.role=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to find users with this role...";
	exit;
} 
$row=mysql_fetch_assoc($rs);
if($row['count(*)']>0){
	echo "There are users with this role. It can't be deleted.";
	exit;
}

// delete the attributes
$sql = "DELETE FROM $dbase.`".$table_prefix."user_roles` WHERE $dbase.`".$table_prefix."user_roles`.id=".$id.";";
$rs = mysql_query($sql);
if(!$rs) {
	echo "Something went wrong while trying to delete the role...";
	exit;
} else {		
	$header="Location: index.php?a=86";
	header($header);
}

?>
