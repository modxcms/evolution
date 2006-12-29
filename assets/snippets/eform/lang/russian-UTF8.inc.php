<?php
/**
 * EForm Snippet - language strings for use in the snippet
 *
 * Filename:       assets/snippets/eform/lang/russian-UTF8.inc.php
 * Language:       Russian
 * Encoding:       UTF8
 * Translated by:  Jaroslav Sidorkin
 * Date:           10 Nov 2006
 * Version:        1.0
*/


$_lang["ef_thankyou_message"] = "<h3>Спасибо!</h3><p>Ваша информация успешно отправлена.</p>";
$_lang["ef_no_doc"] = "Документ или чанк не найдены для шаблона с id=";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>В вашей форме обнаружены следующие ошибки:</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " Необходимо заполнить следующие поля: {fields}<br />";
$_lang["ef_invalid_number"] = " не является правильным числом";
$_lang["ef_invalid_date"] = " не является правильной датой";
$_lang["ef_invalid_email"] = " не является правильным адресом электронной почты";
$_lang["ef_upload_exceeded"] = " превышает допустимый лимит объёма загрузки.";
$_lang["ef_failed_default"] = "Неверное значение";
$_lang["ef_failed_vericode"] = "Неверный код подтверждения.";
$_lang["ef_failed_range"] = "Значение выходит за пределы допустимого диапазона";
$_lang["ef_failed_list"] = "Значение не находится в списке допустимых значений";
$_lang["ef_failed_eval"] = "Значение не прошло проверку";
$_lang["ef_failed_ereg"] = "Значение не прошло проверку";
$_lang["ef_failed_upload"] = "Недопустимый тип файла.";
$_lang["ef_error_validation_rule"] = "Правило проверки не распознано";
$_lang["ef_tamper_attempt"] = "Выявлена попытка подделки!";
$_lang["ef_error_formid"] = "Ошибочные Id или имя формы.";
$_lang["ef_debug_info"] = "Отладочная информация: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Шаблоном формы выбран документ с id таким же, как у документа, содержащего вызов этого сниппета! Нельзя использовать форму в том же документе, в котором производится вызов сниппета.</span> id=";
$_lang["ef_sql_no_result"] = " без проблем прошел проверку. <span style=\"color:red;\"> SQL-запрос не возвратил никаких результатов!</span> ";
$_lang['ef_regex_error'] = 'ошибка в регулярном выражении ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ВНИМАНИЕ - ОТЛАДКА ВКЛЮЧЕНА</span> <br />Не забудьте выключить отладку перед публикацией этой формы для реального использования!</p>';
$_lang['ef_mail_abuse_subject'] = 'Выявлена потенциальная попытка несанкционированной рассылки email с помощью формы с id';
$_lang['ef_mail_abuse_message'] = '<p>Возможно, была сделана попытка внедрения несанкционированной электронной почты в форму на Вашем сайте. Ниже приведена дополнительная информация о посланных с помощью формы данных. Подозрительный текст заключен в тэги \[..]\.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>В Вашей форме обнаружены ошибочные или небезопасные поля.</strong>.';
$_lang['ef_eval_deprecated'] = "Выражение #EVAL помечено как устаревшее и может не работать в следующих версиях. Вместо него используйте #FUNCTION.";
?>
