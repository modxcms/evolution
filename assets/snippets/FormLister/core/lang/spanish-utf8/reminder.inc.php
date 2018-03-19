<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 21.05.2016
 * Time: 11:51
 */

$_lang = array();
$_lang['reminder.default_skipTpl'] = '@CODE:Tienes que desconectarte para restaurar tu contraseña.';
$_lang['reminder.default_reportTpl'] = '@CODE:Para restablecer su contraseña, utilice el siguiente enlace: <a href="[+reset.url+]">[+reset.url+]</a>';
$_lang['reminder.users_only'] = 'Solo los usuarios registrados pueden restaurar contraseñas.';
$_lang['reminder.update_failed'] = 'Fallo al proceder.';
$_lang['reminder.default_successTpl'] = '@CODE:El enlace para restaurar su contraseña ha sido enviado.';
$_lang['reminder.default_resetSuccessTpl'] = '@CODE:La nueva contraseña ha sido enviada.';

return $_lang;
