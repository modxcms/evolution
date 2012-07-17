<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

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

require ('hash.inc.php');
$HashHandler = new HashHandler(CLIPPER_HASH_PREFERRED, $modx);
$Hash = $HashHandler->generate($pass1);

$sql = 'UPDATE '.$dbase.'.'.$table_prefix.'manager_users SET hashtype='.CLIPPER_HASH_PREFERRED.', salt=\''.$modx->db->escape($Hash->salt).'\', password=\''.$modx->db->escape($Hash->hash).'\' WHERE id='.$modx->getLoginUserID();
$rs = mysql_query($sql);
if(!$rs){
	echo "An error occured while attempting to save the new password.";
	exit;
}

$_SESSION['mgrHashtype'] = CLIPPER_HASH_PREFERRED;

$header="Location: index.php?a=7";
header($header);
?>
