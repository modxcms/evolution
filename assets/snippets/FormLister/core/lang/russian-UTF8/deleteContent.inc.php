<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 15.05.2016
 * Time: 1:26
 */

setlocale(LC_ALL, 'ru_RU.UTF-8');

$_lang = array();
$_lang['deleteContent.default_skipTpl'] = '@CODE:Только зарегистрированные пользователи могут удалять записи.';
$_lang['deleteContent.default_successTpl'] = '@CODE:Запись успешно удалена.';
$_lang['deleteContent.default_badOwnerTpl'] = '@CODE:Только автор может удалить запись.';
$_lang['deleteContent.default_badRecordTpl'] = '@CODE:Вы не можете удалить эту запись.';
$_lang['deleteContent.delete_failed'] = 'Не удалось удалить запись.';

return $_lang;
