<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 21.05.2016
 * Time: 11:51
 */

setlocale(LC_ALL, 'ru_RU.UTF-8');

$_lang = array();
$_lang['activate.default_skipTpl'] = '@CODE:Ваша учетная запись активирована';
$_lang['activate.default_reportTpl'] = '@CODE:Для активации учетной записи перейдите по ссылке: <a href="[+reset.url+]">[+reset.url+]</a>';
$_lang['activate.no_activation'] = 'Эта учетная запись не требует активации или не может быть активирована.';
$_lang['activate.update_failed'] = 'Не удалось выполнить операцию.';
$_lang['activate.default_successTpl'] = '@CODE:Вам отправлено письмо со ссылкой для активации учетной записи.';
$_lang['activate.default_activateSuccessTpl'] = '@CODE:Учетная запись успешно активирована.';

return $_lang;
