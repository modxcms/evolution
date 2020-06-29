<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('messages')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$sendto = $_REQUEST['sendto'];
$userid = $_REQUEST['user'];
$groupid = $_REQUEST['group'];
$subject = $_REQUEST['messagesubject'];
if ($subject == "") $subject = "(no subject)";
$message = $_REQUEST['messagebody'];
if ($message == "") $message = "(no message)";
$postdate = time();

if ($sendto == 'u') {
    if ($userid == 0) {
        $modx->webAlertAndQuit($_lang["error_no_user_selected"]);
    }
    \EvolutionCMS\Models\UserMessage::query()->create(
        array(
            'recipient' => $userid,
            'sender' => $modx->getLoginUserID('mgr'),
            'subject' => $subject,
            'message' => $message,
            'postdate' => $postdate,
            'type' => 'Message',
            'private' => 1,
        )
    );
}

if ($sendto == 'g') {
    if ($groupid == 0) {
        $modx->webAlertAndQuit($_lang["error_no_group_selected"]);
    }
    $users = \EvolutionCMS\Models\UserAttribute::query()->where('role', $groupid)->where('internalKey', '!=', $modx->getLoginUserID('mgr'))->get();
    foreach ($users as $user) {
        \EvolutionCMS\Models\UserMessage::query()->create(
            array(
                'recipient' => $user->internalKey,
                'sender' => $modx->getLoginUserID('mgr'),
                'subject' => $subject,
                'message' => $message,
                'postdate' => $postdate,
                'type' => 'Message',
                'private' => 0,
            )
        );
    }
}


if ($sendto == 'a') {
    $users = \EvolutionCMS\Models\UserAttribute::query()->where('internalKey', '!=', $modx->getLoginUserID('mgr'))->get();
    foreach ($users as $user) {
        \EvolutionCMS\Models\UserMessage::query()->create(
            array(
                'recipient' => $user->internalKey,
                'sender' => $modx->getLoginUserID('mgr'),
                'subject' => $subject,
                'message' => $message,
                'postdate' => $postdate,
                'type' => 'Message',
                'private' => 0,
            )
        );
    }
}

$header = "Location: index.php?a=10";
header($header);
