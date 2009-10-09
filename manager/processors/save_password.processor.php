<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_password')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php

$id = $_POST['id'];
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];

if($pass1!=$pass2){
	echo "passwords don't match!";
	exit;
}

if(strlen($pass1)<6){
	echo "Password is too short. Please specify a password of at least 6 characters.";
	exit;
}

$sql = "UPDATE $dbase.`".$table_prefix."manager_users` SET password=md5('".$pass1."') where id=".$modx->getLoginUserID().";";
$rs = mysql_query($sql);
if(!$rs){
	echo "An error occured while attempting to save the new password.";
	exit;
}

$header="Location: index.php?a=7";
header($header);
?>