<?php

/*
 * Title: Language File
 * Purpose:
 *  	Default german language file for Ditto / translated by Marc Hinse
 * Encoding: UTF-8
 *  	
 * Note:
 * 		New language keys should added at the bottom of this page
*/

$_lang['language'] = "deutsch";

$_lang['abbr_lang'] = "de";

$_lang['file_does_not_exist'] = "exisiert nicht. Bitte überprüfen.";

$_lang['extender_does_not_exist'] = "extender exisitiert nicht. Bitte überprüfen.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">von <strong>[+author+]</strong> am [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] enthält entweder keine Platzhalter, ist kein gültiger Chunk, Code Block, oder Dateiname. Bitte überprüfen.</p>";

$_lang['no_documents'] = '<p>Keine Dokumente gefunden.</p>';

$_lang['resource_array_error'] = 'Resource Array Fehler';
 
$_lang['prev'] = "&lt; Vorherige";

$_lang['next'] = "Nächste &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";

$_lang['invalid_class'] = "Ditto Klasse ist ungültig. Bitte überprüfen.";

$_lang['none'] = "Keine";

$_lang['edit'] = "Bearbeiten";

$_lang['dateFormat'] = "%d.%b.%y %H:%M";

// Debug Tab Names

$_lang['info'] = "Informationen";

$_lang['modx'] = "MODx";

$_lang['fields'] = "Felder";

$_lang['templates'] = "Templates";

$_lang['filters'] = "Filter";

$_lang['prefetch_data'] = "Abgerufene Daten";

$_lang['retrieved_data'] = "Verarbeitete Daten";

// Debug Text

$_lang['placeholders'] = "Platzhalter";

$_lang['params'] = "Parameter";

$_lang['basic_info'] = "Generelle Informationen";

$_lang['document_info'] = "Dokument Informationen";

$_lang['debug'] = "Debug";

$_lang['version'] = "Version";

$_lang['summarize'] = "Zusammengefasst";

$_lang['total'] = "Gesamt";	 

$_lang['sortBy'] = "Sortiert nach";

$_lang['sortDir'] = "Sortierreihenfolge";

$_lang['start'] = "Start";
	 
$_lang['stop'] = "Stop";

$_lang['ditto_IDs'] = "IDs";

$_lang['ditto_IDs_selected'] = "Ausgewählte IDs";

$_lang['ditto_IDs_all'] = "Alle IDs";

$_lang['open_dbg_console'] = "Debug Konsole öffnen";

$_lang['save_dbg_console'] = "Debug Konsole speichern";

?>