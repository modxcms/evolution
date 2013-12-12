<?php
/**
 * Language file for eForm
 *
 * Language:       Italian
 * Encoding:       UTF-8
 * Translated by:  Diego Meozzi, Nicola Lambathaki (Banzai)
 * Date:           -
 */

$_lang["ef_date_format"] = "%d-%b-%Y %H:%M:%S";
$_lang["ef_debug_info"] = "Informazione di debug: ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">ATTENZIONE - DEBUGGING ATTIVATO</span> <br />Sinceratevi di disattivare il debugging prima di rendere attivo questo modulo!</p>";
$_lang["ef_error_filter_rule"] = "Text filter not recognized";
$_lang["ef_error_formid"] = "Numero ID o nome del modulo non valido.";
$_lang["ef_error_list_rule"] = "Error in validating form field! #LIST rule declared but no list values found: ";
$_lang["ef_error_validation_rule"] = "Regola di convalida non riconosciuta";
$_lang["ef_eval_deprecated"] = "La regola #EVAL è sconsigliata e potrebbe non funzionare nelle versioni future. Vi preghiamo di usare al suo posto #FUNCTION.";
$_lang["ef_failed_default"] = "Valore errato";
$_lang["ef_failed_ereg"] = "Il valore non è stato convalidato";
$_lang["ef_failed_eval"] = "Il valore non è stato convalidato";
$_lang["ef_failed_list"] = "Il valore non si trova nella lista di valori consentiti";
$_lang["ef_failed_range"] = "Il valore non si trova entro la gamma consentita";
$_lang["ef_failed_upload"] = "Tipo di documento errato.";
$_lang["ef_failed_vericode"] = "Codice di verifica errato.";
$_lang["ef_invalid_date"] = " non è una data valida";
$_lang["ef_invalid_email"] = " non è un indirizzo email valido";
$_lang["ef_invalid_number"] = " non è un numero valido";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Il template del modulo è stato impostato sull'id di una pagina contenente una chiamata a uno snippet! Non è possibile avere il modulo e una chiamata a uno snippet nello stesso documento.</span> id=";
$_lang["ef_mail_abuse_error"] = "<strong>Invalid or insecure entries were detected in your form.</strong>";
$_lang["ef_mail_abuse_message"] = "<p>Un modulo presente sul vostro sito potrebbe essere stato soggetto ad un tentativo di abuso email. I dettagli dei valori inviati sono riportati qui in basso. Il testo sospetto è stato inserito tra i tag \[..]\.  </p>";
$_lang["ef_mail_abuse_subject"] = "Possibile abuso del modulo email rilevato dal modulo di id";
$_lang["ef_mail_error"] = "Mailer was unable to send mail";
$_lang["ef_multiple_submit"] = "<p>This form was already submitted succesfully. There is no need to submit your information multiple times.</p>";
$_lang["ef_no_doc"] = "Documento o chunk non trovato per il template di id=";
$_lang["ef_regex_error"] = "errore nell'espressione regolare ";
$_lang["ef_required_message"] = " Risultano mancanti i seguenti campi obbligatori: {fields}<br />";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Failed</span> using rule [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Passed using rule [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = " ha superato la convalida. <span style=\"color:red;\"> SQL non ha fornito risultati!</span> ";
$_lang["ef_submit_time_limit"] = "<p>This form was already submitted succesfully. Re-submission of the form is disabled for [+submitLimitMinutes+] minutes.</p>";
$_lang["ef_tamper_attempt"] = "È stato rilevato un tentativo di manomissione!";
$_lang["ef_thankyou_message"] = "<h3>Grazie!</h3><p>Le vostre informazioni sono state inviate con successo.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": error in uploading file.";
$_lang["ef_upload_exceeded"] = " è stato superato il limite massimo di caricamento dati.";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>Sono stati riscontrati alcuni errori nella compilazione del modulo:</strong><br /><br />[+ef_wrapper+]</div>";
$_lang["ef_version_error"] = "<strong>WARNING!</strong> The version of the eForm snippet (version:&nbsp;[+version+]) is different from the included eForm file (version:&nbsp;[+fileVersion+]). Please make sure you use the same version for both.";
?>