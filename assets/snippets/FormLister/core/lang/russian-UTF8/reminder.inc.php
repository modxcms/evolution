<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 21.05.2016
 * Time: 11:51
 */

setlocale(LC_ALL, 'ru_RU.UTF-8');

$_lang = array();
$_lang['reminder.default_skipTpl'] = '@CODE:Авторизованные пользователи не могут восстанавливать пароль.';
$_lang['reminder.default_reportTpl'] = '@CODE:Для восстановления пароля перейдите по ссылке: <a href="[+reset.url+]">[+reset.url+]</a>';
$_lang['reminder.users_only'] = 'Только зарегистрированные пользователи могут восстанавливать пароли.';
$_lang['reminder.update_failed'] = 'Не удалось выполнить операцию.';
$_lang['reminder.default_successTpl'] = '@CODE:Вам отправлено письмо со ссылкой для восстановления пароля.';
$_lang['reminder.default_resetSuccessTpl'] = '@CODE:Вам отправлено письмо с новым паролем.';

return $_lang;
