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
$_lang["ef_error_list_rule"] = "Błąd przy sprawdzaniu pola! Reguła #LIST została zadeklarowana, ale nie znaleziono wartości listy:";
$_lang["ef_error_validation_rule"] = "Reguła walidacji nierozpoznana";
$_lang["ef_eval_deprecated"] = "Reguła #EVAL jest przestarzała i może nie działać w przyszłości. Użyj zamiast niej #FUNCTION.";
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
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Szablon formularza ustawiono na id strony, która zawiera snippet! Nie możesz stosować formularza w tym samym dokumencie co snippet.</span> id=";
$_lang["ef_mail_abuse_error"] = "<strong>W formularzu wykryto błędne lub niebezpieczne wpisy.</strong>";
$_lang["ef_mail_abuse_message"] = "<p>Formularz na Twojej stronie, mógł zostać poddany próbie \"email injection\". Szczegóły przesłanych wartości są widoczne poniżej. Podejrzany tekst został umieszczony w tagach \[..]\.</p>";
$_lang["ef_mail_abuse_subject"] = "Wykryto potencjalne nadużycie formularza dla form id";
$_lang["ef_mail_error"] = "Mailer nie był w stanie wysłać wiadomości";
$_lang["ef_multiple_submit"] = "<p>Formularz już został pomyślnie wysłany. Nie ma potrzeby wysyłać go kilka razy.</p>";
$_lang["ef_no_doc"] = "Dokument lub chunk nie został znaleziony dla szablonu id=";
$_lang["ef_regex_error"] = "błąd w wyrażeniu regularnym";
$_lang["ef_required_message"] = "Następujące pola nie zostały wypełnione: [+fields+]";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Niepowodzenie</span> używając reguły [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Powodzenie używając reguły [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = "walidacja udana. <span style=\"color:red;\"> SQL nie zwróciło wyników!</span> ";
$_lang["ef_submit_time_limit"] = "<p>Formularz już został pomyślnie wysłany. Ponowne wysłanie będzie możliwe po upływie [+submitLimitMinutes+] minut.</p>";
$_lang["ef_tamper_attempt"] = "Wykryto próbę manipulacji!";
$_lang["ef_thankyou_message"] = "<h3>Dziękujemy!</h3><p>Wiadomość została pomyślnie wysłana.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": błąd podczas wgrywania pliku.";
$_lang["ef_upload_exceeded"] = "przekroczono limit wielkości wgrywanego pliku.";
$_lang["ef_validation_message"] = "W formularzu znaleziono błędy:";
$_lang["ef_version_error"] = "<strong>UWAGA!</strong> Wersja snippetu eForm (version:&nbsp;[+version+]) jest inna od pliku eForm (version:&nbsp;[+fileVersion+]). Upewnij się że używasz tej samej wersji w obu.";
?>