<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 21.05.2016
 * Time: 11:51
 */

$_lang = array();
$_lang['reminder.default_skipTpl'] = '@CODE:You have to log out to restore your password.';
$_lang['reminder.default_reportTpl'] = '@CODE:To restore your password please use the following link: <a href="[+reset.url+]">[+reset.url+]</a>';
$_lang['reminder.users_only'] = 'Only registered users can restore passwords.';
$_lang['reminder.update_failed'] = 'Failed to proceed.';
$_lang['reminder.default_successTpl'] = '@CODE:The link to restore your password has been sent.';
$_lang['reminder.default_resetSuccessTpl'] = '@CODE:The new password has been sent.';

return $_lang;
