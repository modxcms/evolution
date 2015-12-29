<?php
/**
 * Language file for eForm
 *
 * Language:       Russian
 * Encoding:       UTF-8
 * Translated by:  Jaroslav Sidorkin
 * Date:           2014/06/13
 */
$_lang["ef_date_format"] = "%d-%b-%Y %H:%M:%S";
$_lang["ef_debug_info"] = "Отладочная информация: ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">Внимание - включена отладка</span> <br />Не забудьте выключить отладку перед публикацией этой формы для реального использования!</p>";
$_lang["ef_error_filter_rule"] = "Текстовый фильтр неизвестен";
$_lang["ef_error_formid"] = "Ошибочные Id или имя формы.";
$_lang["ef_error_list_rule"] = "Ошибка в заполнении поля! Правило #LIST задано, но значений списка не найдено: ";
$_lang["ef_error_validation_rule"] = "Правило проверки не распознано";
$_lang["ef_eval_deprecated"] = "Выражение #EVAL помечено как устаревшее и может не работать в следующих версиях. Вместо него используйте #FUNCTION.";
$_lang["ef_failed_default"] = "Неверное значение";
$_lang["ef_failed_ereg"] = "Значение не прошло проверку";
$_lang["ef_failed_eval"] = "Значение не прошло проверку";
$_lang["ef_failed_list"] = "Значение не находится в списке допустимых значений";
$_lang["ef_failed_range"] = "Значение выходит за пределы допустимого диапазона";
$_lang["ef_failed_upload"] = "Недопустимый тип файла.";
$_lang["ef_failed_vericode"] = "Неверный код подтверждения.";
$_lang["ef_invalid_date"] = " не является правильной датой";
$_lang["ef_invalid_email"] = " не является правильным e-mail адресом";
$_lang["ef_invalid_phone"] = "Недопустимые значения в телефонном номере";
$_lang["ef_invalid_number"] = " не является правильным числом";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Шаблоном формы выбран ресурс с id таким же, как у ресурса, содержащего вызов этого сниппета! Нельзя использовать форму в том же ресурсе, в котором производится вызов сниппета.</span> id=";
$_lang["ef_mail_abuse_error"] = "<strong>В Вашей форме обнаружены ошибочные или небезопасные поля.</strong>.";
$_lang["ef_mail_abuse_message"] = "<p>Возможно, была сделана попытка внедрения несанкционированной электронной почты в форму на Вашем сайте. Ниже приведена дополнительная информация о посланных с помощью формы данных. Подозрительный текст заключен в теги \[..]\.</p>";
$_lang["ef_mail_abuse_subject"] = "Выявлена потенциальная попытка несанкционированной рассылки e-mail с помощью формы с id";
$_lang["ef_mail_error"] = "Программа не смогла отправить почту";
$_lang["ef_multiple_submit"] = "<p>Данные успешно отправлены. Нет нужды отправлять данные несколько раз.</p>";
$_lang["ef_no_doc"] = "Ресурс или чанк не найдены для шаблона с id=";
$_lang["ef_regex_error"] = "ошибка в регулярном выражении ";
$_lang["ef_required_message"] = "Необходимо заполнить следующие поля: [+fields+]";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Ошибка!</span> Не выполнено правило [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Успешно выполнено правило [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = " без проблем прошел проверку. <span style=\"color:red;\"> SQL-запрос не возвратил никаких результатов!</span> ";
$_lang["ef_submit_time_limit"] = "<p>Данные были УЖЕ успешно отправлены. Повторная отправка данных невозможна втечение [+submitLimitMinutes+] минут.</p>";
$_lang["ef_tamper_attempt"] = "Выявлена попытка подделки!";
$_lang["ef_thankyou_message"] = "<h3>Спасибо!</h3><p>Ваша информация успешно отправлена.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": ошибка при загрузке файла.";
$_lang["ef_upload_exceeded"] = " превышает допустимый лимит объема загрузки.";
$_lang["ef_validation_message"] = "В вашей форме обнаружены следующие ошибки:";
$_lang["ef_version_error"] = "<strong>Внимание!</strong> Версия сниппета eForm ([+version+]) отличается от inc-файла ([+fileVersion+]). Пожалуйста, убедитесь в том, что версии идентичны.";
?>