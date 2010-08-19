<?php
/**
* snippets/eform/english.inc.php
* English language file for eForm
* Translation: Diego Meozzi, Nicola Lambathaki (Banzai)
*/

$_lang["ef_thankyou_message"] = "<h3>Grazie!</h3><p>Le vostre informazioni sono state inviate con successo.</p>";
$_lang["ef_no_doc"] = "Documento o chunk non trovato per il template di id=";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>Sono stati riscontrati alcuni errori nella compilazione del modulo:</strong><br /><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " Risultano mancanti i seguenti campi obbligatori: {fields}<br />";
$_lang["ef_invalid_number"] = " non è un numero valido";
$_lang["ef_invalid_date"] = " non è una data valida";
$_lang["ef_invalid_email"] = " non è un indirizzo email valido";
$_lang["ef_upload_exceeded"] = " è stato superato il limite massimo di caricamento dati.";
$_lang["ef_failed_default"] = "Valore errato";
$_lang["ef_failed_vericode"] = "Codice di verifica errato.";
$_lang["ef_failed_range"] = "Il valore non si trova entro la gamma consentita";
$_lang["ef_failed_list"] = "Il valore non si trova nella lista di valori consentiti";
$_lang["ef_failed_eval"] = "Il valore non è stato convalidato";
$_lang["ef_failed_ereg"] = "Il valore non è stato convalidato";
$_lang["ef_failed_upload"] = "Tipo di documento errato.";
$_lang["ef_error_validation_rule"] = "Regola di convalida non riconosciuta";
$_lang["ef_tamper_attempt"] = "È stato rilevato un tentativo di manomissione!";
$_lang["ef_error_formid"] = "Numero ID o nome del modulo non valido.";
$_lang["ef_debug_info"] = "Informazione di debug: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Il template del modulo è stato impostato sull'id di una pagina contenente una chiamata a uno snippet! Non è possibile avere il modulo e una chiamata a uno snippet nello stesso documento.</span> id=";
$_lang["ef_sql_no_result"] = " ha superato la convalida. <span style=\"color:red;\"> SQL non ha fornito risultati!</span> ";
$_lang['ef_regex_error'] = 'errore nell\'espressione regolare ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ATTENZIONE - DEBUGGING ATTIVATO</span> <br />Sinceratevi di disattivare il debugging prima di rendere attivo questo modulo!</p>';
$_lang['ef_mail_abuse_subject'] = 'Possibile abuso del modulo email rilevato dal modulo di id';
$_lang['ef_mail_abuse_message'] = '<p>Un modulo presente sul vostro sito potrebbe essere stato soggetto ad un tentativo di abuso email. I dettagli dei valori inviati sono riportati qui in basso. Il testo sospetto è stato inserito tra i tag \[..]\.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Nel modulo sono state rilevate delle voci non valide o insicure</strong>.';
$_lang['ef_eval_deprecated'] = "La regola #EVAL è sconsigliata e potrebbe non funzionare nelle versioni future. Vi preghiamo di usare al suo posto #FUNCTION.";
?>