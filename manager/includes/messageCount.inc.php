<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

$sql="SELECT count(*) FROM $dbase.`".$table_prefix."user_messages` where recipient=".$modx->getLoginUserID()." and messageread=0;";
$rs = $modx->db->query($sql);
$row = $modx->db->getRow($rs);
$nrnewmessages = $row['count(*)'];
$sql="SELECT count(*) FROM $dbase.`".$table_prefix."user_messages` where recipient=".$modx->getLoginUserID()."";
$rs = $modx->db->query($sql);
$row = $modx->db->getRow($rs);
$nrtotalmessages = $row['count(*)'];
$messagesallowed = $modx->hasPermission('messages');

// ajax response
if (isset($_POST['updateMsgCount'])) {
	print $nrnewmessages.','.$nrtotalmessages;
	exit();
}
?>
