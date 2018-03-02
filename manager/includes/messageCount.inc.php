<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

$rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('user_messages'),
    "recipient=" . $modx->getLoginUserID() . " AND messageread=0");
$nrnewmessages = $modx->db->getValue($rs);
$rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('user_messages'), "recipient=" . $modx->getLoginUserID());
$nrtotalmessages = $modx->db->getValue($rs);
$messagesallowed = $modx->hasPermission('messages');

// ajax response
if (isset($_POST['updateMsgCount'])) {
    header("Content-Type: application/json; charset=utf-8");
    print $nrnewmessages . ',' . $nrtotalmessages;
    exit;
}
