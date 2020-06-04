<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

$nrnewmessages = \EvolutionCMS\Models\UserMessage::where('recipient', $modx->getLoginUserID())->where('messageread', 0)->count();
$nrtotalmessages = \EvolutionCMS\Models\UserMessage::where('recipient', $modx->getLoginUserID())->count();
$messagesallowed = $modx->hasPermission('messages');

// ajax response
if (isset($_POST['updateMsgCount'])) {
    header("Content-Type: application/json; charset=utf-8");
    print $nrnewmessages . ',' . $nrtotalmessages;
    exit;
}
