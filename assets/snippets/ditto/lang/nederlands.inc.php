<?php

/*
* Title: Language File
* Purpose:
* Dutch language file for Ditto
* 
* Note:
* New language keys should added at the bottom of this page
*/

$_lang['language'] = "Nederlands";
$_lang['abbr_lang'] = "nl";
$_lang['file_does_not_exist'] = "bestaat niet. Controleer a.u.b. het bestand.";
$_lang['extender_does_not_exist'] = "vergroter bestaat niet. Controleer dit a.u.b.";
$_lang['default_template'] = <<<TPL
<div class="ditto_item" id="ditto_item_[+id+]">
<h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
<div class="ditto_documentInfo">door <strong>[+author+]</strong> op [+date+]</div>
<div class="ditto_introText">[+introtext+]</div>
</div>
TPL;
$_lang["bad_tpl"] = "<p>&[+tpl+] bevat geen placeholders of is een ongeldige chunk naam, code blok, of bestandsnaam. Controleer dit a.u.b.</p>";
$_lang['no_documents'] = '<p>Geen documenten gevonden.</p>';
$_lang['resource_array_error'] = 'Resource Array Fout';
$_lang['prev'] = "&lt; Vorige";
$_lang['next'] = "Volgende &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2006";
$_lang['invalid_class'] = "De Ditto class is ongeldig. Controleer dit a.u.b.";
$_lang['none'] = "Geen";
$_lang['edit'] = "wijzig";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";
// Debug Tab Names
$_lang['info'] = "Info";
$_lang['modx'] = "MODx";
$_lang['fields'] = "Velden";
$_lang['templates'] = "Sjablonen";
$_lang['filters'] = "Filters";
$_lang['prefetch_data'] = "Prefetch data";
$_lang['retrieved_data'] = "Opgehaalde data";
// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Parameters";
$_lang['basic_info'] = "Basisinfo";
$_lang['document_info'] = "Documentinfo";
$_lang['debug'] = "Debug";
$_lang['version'] = "Versie";
$_lang['summarize'] = "Opsomming";
$_lang['total'] = "Totaal"; 
$_lang['sortBy'] = "Sorteren op";
$_lang['sortDir'] = "Sorteerrichting";
$_lang['start'] = "Start";
$_lang['stop'] = "Stop";
$_lang['ditto_IDs'] = "IDs";
$_lang['ditto_IDs_selected'] = "Geselecteerde IDs";
$_lang['ditto_IDs_all'] = "Alle IDs";
$_lang['open_dbg_console'] = "Open Debug Console";
$_lang['save_dbg_console'] = "Bewaar Debug Console";

?>
