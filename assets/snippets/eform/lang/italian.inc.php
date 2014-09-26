<?php
/**
 * Language file for eForm
 *
 * Language:       Italian
 * Encoding:       UTF-8
 * Translated by:  Diego Meozzi, Nicola Lambathaki (Banzai), luigif
 * Date:           2014/06/13
 */
$_lang["ef_date_format"] = "%d-%b-%Y %H:%M:%S";
$_lang["ef_debug_info"] = "Informazioni di debug: ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">ATTENZIONE - DEBUGGING ATTIVATO</span> <br />Verificate di aver disattivato il debugging prima di attivare questo form!</p>";
$_lang["ef_error_filter_rule"] = "Filtro del testo non riconosciuto";
$_lang["ef_error_formid"] = "ID o nome del form errati.";
$_lang["ef_error_list_rule"] = "Errore nella validazione del form! E' stata dichiarata una regola #LIST ma mancano i valori della lista: ";
$_lang["ef_error_validation_rule"] = "Regola di validazione non riconosciuta";
$_lang["ef_eval_deprecated"] = "L'uso della regola #EVAL è sconsigliato e potrebbe non funzionare nelle prossime versioni. Vi preghiamo di usare #FUNCTION.";
$_lang["ef_failed_default"] = "Valore errato";
$_lang["ef_failed_ereg"] = "Il valore non è stato convalidato";
$_lang["ef_failed_eval"] = "Il valore non è stato convalidato";
$_lang["ef_failed_list"] = "Il valore non si trova nella lista dei valori consentiti";
$_lang["ef_failed_range"] = "Il valore non si trova all'interno del range di valori consentiti";
$_lang["ef_failed_upload"] = "Tipo di documento errato.";
$_lang["ef_failed_vericode"] = "Codice di verifica errato.";
$_lang["ef_invalid_date"] = " non è una data valida";
$_lang["ef_invalid_email"] = " non è un indirizzo email valido";
$_lang["ef_invalid_number"] = " non è un numero valido";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Il template del form è stato impostato sull'id di una pagina contenente una chiamata a uno snippet! Non è possibile avere un form e una chiamata a uno snippet nello stesso documento.</span> id=";
$_lang["ef_mail_abuse_error"] = "<strong>Sono stati inseriti dei dati non validi o potenzialmente pericolosi.</strong>";
$_lang["ef_mail_abuse_message"] = "<p>Un form sul vostro sito potrebbe essere stato utilizzato per inviare mail di spam. I dettagli dei valori inviati sono riportati qui sotto. Il testo sospetto è stato inserito tra i tag \[..]\.  </p>";
$_lang["ef_mail_abuse_subject"] = "Possibile abuso email rilevato nel form con id";
$_lang["ef_mail_error"] = "Impossibile inviare l'emal";
$_lang["ef_multiple_submit"] = "<p>Questi dati sono già stati inviati. Non occorre inviare ancora gli stessi dati.</p>";
$_lang["ef_no_doc"] = "Risorsa o Chunk non trovati per il template con id=";
$_lang["ef_regex_error"] = "errore nell'espressione regolare ";
$_lang["ef_required_message"] = "Compilare i seguenti campi obbligatori: [+fields+]";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Errore</span> utilizzando la regola [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Verificato usando la regola [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = "ha superato la convalida. <span style=\"color:red;\"> SQL non ha fornito risultati!</span> ";
$_lang["ef_submit_time_limit"] = "<p>Questo form è già stato inviato con successo. Un nuovo invio sarà possibile fra [+submitLimitMinutes+] minuti.</p>";
$_lang["ef_tamper_attempt"] = "È stato rilevato un tentativo di manomissione!";
$_lang["ef_thankyou_message"] = "<h3>Grazie!</h3><p>Le vostre informazioni sono state inviate con successo.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": errore nel caricamento del file.";
$_lang["ef_upload_exceeded"] = "ha superato la dimensione massima dei dati caricabili.";
$_lang["ef_validation_message"] = "Sono stati riscontrati alcuni errori nella compilazione del form:";
$_lang["ef_version_error"] = "<strong>WARNING!</strong> La versione dello snippet eForm che utilizzate (versione:&nbsp;[+version+]) è diversa dal file eForm incluso (versione:&nbsp;[+fileVersion+]). Dovete utilizzare la stessa versione di entrambi.";
?>