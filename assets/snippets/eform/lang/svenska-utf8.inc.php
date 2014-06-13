<?php
/**
 * Language file for eForm
 *
 * Language:       Swedish
 * Encoding:       UTF-8
 * Translated by:  Pontus Ågren
 * Date:           2014/06/13
 */
$_lang["ef_date_format"] = "%Y-%b-%d %H:%M:%S";
$_lang["ef_debug_info"] = "Debugg-info: ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">VARNING - DEBUGGNING ÄR PÅ</span> <br />Var noga med att stänga av debuggningen innan du börjar använda formuläret live!</p>";
$_lang["ef_error_filter_rule"] = "Textfiltret kändes inte igen";
$_lang["ef_error_formid"] = "Ogiltigt ID-nummer eller -namn i formuläret.";
$_lang["ef_error_list_rule"] = "Ett fel uppstod när formulärfältet validerades! En #LIST-regel är deklarerad, men inga listvärden funna: ";
$_lang["ef_error_validation_rule"] = "Valideringsregeln känns inte igen";
$_lang["ef_eval_deprecated"] = "#EVAL-regeln används inte längre och kommer kanske inte att fungera i framtida versioner. Använd #FUNCTION istället.";
$_lang["ef_failed_default"] = "Falaktigt värde";
$_lang["ef_failed_ereg"] = "Värdet validerar inte";
$_lang["ef_failed_eval"] = "Värdet validerar inte";
$_lang["ef_failed_list"] = "Värdet finns inte i listan med tillåtna värden";
$_lang["ef_failed_range"] = "Värdet är inte inom det tillåtna området";
$_lang["ef_failed_upload"] = "Felaktig filtyp.";
$_lang["ef_failed_vericode"] = "Felaktig verifieringskod.";
$_lang["ef_invalid_date"] = " är inte ett giltigt datum";
$_lang["ef_invalid_email"] = " är inte en giltig epostadress";
$_lang["ef_invalid_number"] = " är inte ett giltigt nummer";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Formulärmallen är satt till samma ID som sidan som innehåller snippet-anropet! Du kan inte ha formuläret i samma dokument som snippet-anropet.</span> ID=";
$_lang["ef_mail_abuse_error"] = "<strong>Felaktiga eller osäkra värden upptäcktes i ditt formulär</strong>.";
$_lang["ef_mail_abuse_message"] = "<p>Ett formulär på din webbplats kan vara föremål för ett mailinjiceringsförsök. De inmatade värdena är utskrivna nedan. Misstänkt text har bäddats in i \[..]\-taggar.</p>";
$_lang["ef_mail_abuse_subject"] = "Potentiellt manipuleringsförsök av mailformulär upptäckt för formulär-ID";
$_lang["ef_mail_error"] = "Mailscriptet kunde inte skicka eposten";
$_lang["ef_multiple_submit"] = "<p>Det här formuläret har redan skickats utan problem. Du behöver inte skicka din information flera gånger.</p>";
$_lang["ef_no_doc"] = "Varken dokument eller chunk kunde inte hittas för mall-ID: ";
$_lang["ef_regex_error"] = "fel i reguljära uttrycket ";
$_lang["ef_required_message"] = "De följande, nödvändiga, fält(en) saknas: [+fields+]";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Misslyckades</span> med regeln [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Utfördes med regeln [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = " klarade valideringen i smyg. <span style=\"color:red;\"> SQL returnerade inget resultat!</span> ";
$_lang["ef_submit_time_limit"] = "<p>Det här formuläret har redan skickats utan problem. Omskickning av formuläret är blockerat i  [+submitLimitMinutes+] minuter.</p>";
$_lang["ef_tamper_attempt"] = "Manipuleringsförsök upptäckt!";
$_lang["ef_thankyou_message"] = "<h3>Tack!</h3><p>Din information skickades utan problem.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": fel vid uppladdning av fil.";
$_lang["ef_upload_exceeded"] = " har överskridit den maximala uppladdningsstorleken.";
$_lang["ef_validation_message"] = "Några fel upptäcktes i ditt formulär:";
$_lang["ef_version_error"] = "<strong>VARNING!</strong> Versionen på eForm-snippeten (version:&nbsp;[+version+]) skiljer sig från den inkluderade eForm-filen (version:&nbsp;[+fileVersion+]). Kontrollera att du använder samma version på båda.";
?>