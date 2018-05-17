<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 15.05.2016
 * Time: 1:26
 */

setlocale(LC_ALL, 'ru_RU.UTF-8');

$_lang = array();
$_lang['create.default_skipTpl'] = '@CODE:Только зарегистрированные пользователи могут создавать записи.';
$_lang['create.default_successTpl'] = '@CODE:Данные успешно сохранены.';
$_lang['edit.default_skipEditTpl'] = '@CODE:Только зарегистрированные пользователи могут редактировать записи.';
$_lang['edit.default_badOwnerTpl'] = '@CODE:Только автор может редактировать эту запись.';
$_lang['edit.default_badRecordTpl'] = '@CODE:Вы не можете редактировать эту запись.';
$_lang['create.default_badGroupTpl'] = '@CODE:У вас нет разрешения создавать записи.';
$_lang['edit.default_badGroupTpl'] = '@CODE:У вас нет разрешения редактировать записи.';
$_lang['edit.update_failed'] = 'Не удалось сохранить данные.';
$_lang['edit.update_success'] = 'Данные успешно сохранены.';

return $_lang;
