<?php

/*
 * Title: Language File
 * Purpose:
 *  	Dutch language file for Ditto
 *  	
 * Note:
 * 		New language keys should added at the bottom of this page
*/

$_lang['language'] = "nederlands";

$_lang['abbr_lang'] = "nl";

$_lang['file_does_not_exist'] = "bestaat niet. Kijk a.u.b. het bestand na.";

$_lang['extender_does_not_exist'] = "extender bestaat niet. Controleer dit a.u.b.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">door <strong>[+author+]</strong> op [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] bevat geen placeholders of is een ongeldige chunk naam, code blok, of bestandsnaam. Controleer dit a.u.b.</p>";

$_lang['no_documents'] = '<p>Geen documenten gevonden.</p>';

$_lang['resource_array_error'] = 'Resource Array Error';
 
$_lang['prev'] = "&lt; Vorige";

$_lang['next'] = "Volgende &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";

$_lang['invalid_class'] = "De Ditto class is ongeldig. Controleer dit a.u.b.";

$_lang['none'] = "Geen";

$_lang['edit'] = "Wijzig";

$_lang['dateFormat'] = "%d-%m-%y %H:%M";

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

$_lang['basic_info'] = "Basis info";

$_lang['document_info'] = "Document info";

$_lang['debug'] = "Debug";

$_lang['version'] = "Versie";

$_lang['summarize'] = "Overzicht";

$_lang['total'] = "Totaal";	 

$_lang['sortBy'] = "Gesorteerd op";

$_lang['sortDir'] = "Sorteer richting";

$_lang['start'] = "Start";
	 
$_lang['stop'] = "Stop";

$_lang['ditto_IDs'] = "ID's";

$_lang['ditto_IDs_selected'] = "Geselecteerde ID's";

$_lang['ditto_IDs_all'] = "Alle ID's";

$_lang['open_dbg_console'] = "Debug Console openen";

$_lang['save_dbg_console'] = "Debug Console opslaan";

?>