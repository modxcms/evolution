<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('messages')) {
	$e->setError(3);
	$e->dumpError();	
}

$id=$_REQUEST['id'];

// check the user is allowed to delete this message
$sql = "SELECT * FROM $dbase.`".$table_prefix."user_messages` WHERE $dbase.`".$table_prefix."user_messages`.id=$id";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit!=1) {
	echo "Wrong number of messages returned!";
	exit;
} else {
	$message=mysql_fetch_assoc($rs);
	if($message['recipient']!=$modx->getLoginUserID()) {
		echo "You are not allowed to delete this message!";
		exit;
	} else {
		// delete message
		$sql = "DELETE FROM $dbase.`".$table_prefix."user_messages` WHERE id=$id;";
		$rs = mysql_query($sql);
		if(!$rs) {
			echo "Something went wrong while trying to delete the message!";
			exit;
		} 
	}
}

$header = "Location: index.php?a=10";
header($header);

?>
