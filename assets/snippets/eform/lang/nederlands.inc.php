<?php
/**
* snippets/eform/nederlands.inc.php
* Dutch language file for eForm
*/


$_lang["ef_thankyou_message"] = "<h3>Dank u!</h3><p>Uw informatie is succesvol verzonden.</p>";
$_lang["ef_no_doc"] = "Document of chunk niet gevonden voor template id=";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>Er zijn fouten gevonden in uw formulier:</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " De volgende verplichte veld(en) ontbreken: {fields}<br />";
$_lang["ef_invalid_number"] = " is geen geldig nummer";
$_lang["ef_invalid_date"] = " is geen geldige datum";
$_lang["ef_invalid_email"] = " is geen geldig e-mail adres";
$_lang["ef_upload_exceeded"] = " overstijgt de maximale upload limiet.";
$_lang["ef_failed_default"] = "Ongeldige waarde";
$_lang["ef_failed_vericode"] = "Ongeldige verificatie code.";
$_lang["ef_failed_range"] = "Waarde niet binnen toegestaan bereik";
$_lang["ef_failed_list"] = "Waarde niet in de lijst van toegestane waarden";
$_lang["ef_failed_eval"] = "Waarde is ongeldig";
$_lang["ef_failed_ereg"] = "Waarde is ongeldig";
$_lang["ef_failed_upload"] = "Onjuist bestandstype.";
$_lang["ef_error_validation_rule"] = "Validatie regel onbekend";
$_lang["ef_tamper_attempt"] = "Onbevoegde handeling gedetecteerd!";
$_lang["ef_error_formid"] = "Ongeldig formulier ID nummer of naam.";
$_lang["ef_debug_info"] = "Debug info: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Formulier template verwijst naar een ID van een pagina met een snippet aanroep! U kunt het formulier niet in hetzelfde document hebben als de snippet aanroep.</span> id=";
$_lang["ef_sql_no_result"] = " heeft een achtergrond validatie uitgevoerd. <span style=\"color:red;\"> SQL gaf geen resultaat terug!</span> ";
$_lang['ef_regex_error'] = 'fout in reguliere expressie ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">WAARSCHUWING - DEBUGGING STAAT AAN</span> <br />Zorg ervoor dat debugging is uitgeschakeld voordat u het formulier beschikbaar maakt!</p>';
$_lang['ef_mail_abuse_subject'] = 'Mogelijk e-mail formulier misbruik gedetecteerd voor forumulier ID';
$_lang['ef_mail_abuse_message'] = '<p>Een formulier op uw site is mogelijk slachtoffer van een e-mail injectie poging. De details van de geposte waarden worden hieronder afgebeeld. Verdachte tekst is ingesloten in \[..]\ tags.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Ongeldige of onveilige waarden gedetecteerd in uw formulier</strong>.';
$_lang['ef_eval_deprecated'] = "De #EVAL regel is gedeprecieerd en werkt mogelijk niet in toekomstige versies. Gebruik liever #FUNCTION.";
?>
