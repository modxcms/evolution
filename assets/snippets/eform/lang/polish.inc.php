<?php
/**
 * Language file for eForm
 *
 * Language:       Polish
 * Encoding:       UTF-8
 * Translated by:  Piotr Matysiak
 * Date:           2014/06/13
 */
$_lang["ef_date_format"] = "%d-%m-%Y %H:%M:%S";
$_lang["ef_debug_info"] = "Debug info: ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">UWAGA - DEBUGOWANIE WŁĄCZONE</span> <br />Nie zapomnij wyłączyć debugowania przed opublikowaniem tego formularza!</p>";
$_lang["ef_error_filter_rule"] = "Filtr tekstu nierozpoznany.";
$_lang["ef_error_formid"] = "Nieprawidłowy numer ID lub nazwa formularza.";
$_lang["ef_error_list_rule"] = "Error in validating form field! #LIST rule declared but no list values found: ";
$_lang["ef_error_validation_rule"] = "Reguła walidacji nierozpoznana";
$_lang["ef_eval_deprecated"] = "The #EVAL rule is deprecated and may not work in future versions. Use #FUNCTION instead.";
$_lang["ef_failed_default"] = "Nieprawidłowa wartość";
$_lang["ef_failed_ereg"] = "Wartość nie przeszła walidacji";
$_lang["ef_failed_eval"] = "Wartość nie przeszła walidacji";
$_lang["ef_failed_list"] = "Wartość nie znajduje się na liście dozwolonych wartości";
$_lang["ef_failed_range"] = "Wartość poza zakresem";
$_lang["ef_failed_upload"] = "Niepoprawny typ pliku.";
$_lang["ef_failed_vericode"] = "Nieprawidłowy kod weryfikacji.";
$_lang["ef_invalid_date"] = "nie jest poprawną datą";
$_lang["ef_invalid_email"] = "nie jest poprawnym adresem e-mail";
$_lang["ef_invalid_number"] = "nie jest poprawną liczbą";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Form template set to id of page containing snippet call! You can not have the form in the same document as the snippet call.</span> id=";
$_lang["ef_mail_abuse_error"] = "<strong>W formularzu wykryto błędne lub niebezpieczne wpisy.</strong>";
$_lang["ef_mail_abuse_message"] = "<p>A form on your website may have been the subject of an email injection attempt. The details of the posted values are printed below. Suspected text has been embedded in \[..]\ tags.</p>";
$_lang["ef_mail_abuse_subject"] = "Potential email form abuse detected for form id";
$_lang["ef_mail_error"] = "Mailer nie był w stanie wysłać wiadomości";
$_lang["ef_multiple_submit"] = "<p>Formularz już został pomyślnie wysłany. Nie ma potrzeby wysyłać go kilka razy.</p>";
$_lang["ef_no_doc"] = "Dokument lub chunk nie został znaleziony dla szablonu id=";
$_lang["ef_regex_error"] = "błąd w wyrażeniu regularnym";
$_lang["ef_required_message"] = "The following required field(s) are missing: [+fields+]";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Niepowodzenie</span> używając reguły [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Powodzenie używając reguły [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = "walidacja udana. <span style=\"color:red;\"> SQL nie zwróciło wyników!</span> ";
$_lang["ef_submit_time_limit"] = "<p>Formularz już został pomyślnie wysłany. Ponowne wysłanie będzie możliwe po upływie [+submitLimitMinutes+] minut.</p>";
$_lang["ef_tamper_attempt"] = "Wykryto próbę manipulacji!";
$_lang["ef_thankyou_message"] = "<h3>Dziękujemy!</h3><p>Wiadomość została pomyślnie wysłana.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": błąd podczas wgrywania pliku.";
$_lang["ef_upload_exceeded"] = "przekroczono limit wielkości wgrywanego pliku.";
$_lang["ef_validation_message"] = "Some errors were detected in your form:";
$_lang["ef_version_error"] = "<strong>WARNING!</strong> The version of the eForm snippet (version:&nbsp;[+version+]) is different from the included eForm file (version:&nbsp;[+fileVersion+]). Please make sure you use the same version for both.";
?>