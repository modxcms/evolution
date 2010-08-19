<?php
/**
* snippets/eform/english.inc.php
* English language file for eForm
*/


$_lang["ef_thankyou_message"] = "<h3>Tak for det!</h3><p>Dine informationer blev succesfuldt sendt.</p>";
$_lang["ef_no_doc"] = "Dokument eller chunk blev ikke fundet for skabelon id=";
//$_lang["ef_no_chunk"] = ""; //deprecated
$_lang["ef_validation_message"] = "<strong>Nogle fejl blev opdaget i din form:</strong><br />";
$_lang["ef_required_message"] = " F&oslash;lgende p&aring;kr&aelig;vede felter mangler: {fields}<br />";
$_lang["ef_invalid_number"] = " er ikke et lovligt nummer";
$_lang["ef_invalid_date"] = " er ikke en gyldig dato";
$_lang["ef_invalid_email"] = " er ikke en gyldig email adresse";
$_lang["ef_upload_exceeded"] = " har overskredet maksimum upload gr&aelig;nse.";
$_lang["ef_failed_default"] = "Ikke korrekt v&aelig;rdi";
$_lang["ef_failed_vericode"] = "Ugyldig verification kode.";
$_lang["ef_failed_range"] = "V&aelig;rdi ikke i tilladte r&aelig;kke";
$_lang["ef_failed_list"] = "V&aelig;rdi ikke i liste af tilladte v&aelig;rdier";
$_lang["ef_failed_eval"] = "V&aelig;rdi kunne ikke valideres";
$_lang["ef_failed_ereg"] = "Value kunne ikke valideres";
$_lang["ef_failed_upload"] = "Ukorrekt fil type.";
$_lang["ef_error_validation_rule"] = "Valideringsregel ikke genkendt";
$_lang["ef_tamper_attempt"] = "manipulationsfors&oslash;g opdaget!";
$_lang["ef_error_formid"] = "Ugyldig Form Id nummer eller navn.";
$_lang["ef_debug_info"] = "Debug info: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Form skabelon sat til id af side indeholdende snippet kald! Du kan ikke have en form i det samme dokument som et snippet kald.</span> id=";
$_lang["ef_sql_no_result"] = " passerede helt stille validering. <span style=\"color:red;\"> SQL returnerede inge resultater!</span> ";
$_lang['ef_regex_error'] = 'Fejl i regul&aelig;r expression ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">WARNING - DEBUGGING er ON</span> <br />V&aelig;r sikker p&aring; at du sl&aring;r debugging off f&oslash;r du sender denne form live!</p>';
$_lang['ef_mail_abuse_subject'] = 'Potentiel email form misbrug opdaget for form id';
$_lang['ef_mail_abuse_message'] = '<p>En form p&aring; dit website kan v&aelig;re emne for et email injection fors&oslash;g. Detaljerne af de angivede v&aelig;rdier er printet nedenunder. Mist&aelig;nkte tekst er blevet indeholdt i \[..]\ tags.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Ugyldig eller usikre indtastninger blev opdaget i din form</strong>.';
$_lang['ef_eval_deprecated'] = "#EVAL reglen er for&aelig;ldet og vil muligvis ikke fungerer i fremtidige versioner. Brug #FUNCTION istedet for.";
?>
