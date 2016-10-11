<?php
if ( ! defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}

$_lang = array();
$_lang['error_none'] = 'При разборе JSON строки ошибок не обнаружено';
$_lang['error_depth'] = 'При разборе JSON строки достигнута максимальная глубина стека';
$_lang['error_state_mismatch'] = 'Некорректные разряды или не совпадение режимов при разборе JSON строки';
$_lang['error_ctrl_char'] = 'Некорректный управляющий символ в JSON строке';
$_lang['error_syntax'] = 'Синтаксическая ошибка, не корректная JSON строка';
$_lang['error_utf8'] = 'Некорректные символы UTF-8 (возможно неверная кодировка) в JSON строке';
$_lang['other'] = 'Произошла непонятная ошибка при разборе JSON строки';

return $_lang;
