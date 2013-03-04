<?php

/*
 * Title: Ditto Language File
 * About: German language file for Ditto.
 *
 * Author: Mark Kaplan
 * Translation: TDRWH
 * Note: New language keys should added at the bottom of this page
*/

$_lang['language'] = "german";

$_lang['abbr_lang'] = "de";

$_lang['file_does_not_exist'] = "nicht vorhanden. Bitte &uuml;berpr&uuml;fen Sie die Datei.";

$_lang['extender_does_not_exist'] = "Die Erweiterung (extender) ist nicht vorhanden. Bitte &uuml;berpr&uuml;fen.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_introText">[+introtext+]</div>
        <div class="ditto_documentInfo">von <strong>[+author+]</strong> am [+date+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] enth&auml;lt entweder keine Platzhalter oder ist ein ung&uuml;ltiger Chunkname, Code-Abschnitt, oder Dateiname. Bitte pr&uuml;fen.</p>";

$_lang['no_documents'] = '<p>Keine Dokumente gefunden.</p>';

$_lang['resource_array_error'] = 'Feldzugriffsfehler';
 
$_lang['prev'] = "&lt; Zur&uuml;ck";

$_lang['next'] = "Weiter &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";

$_lang['invalid_class'] = "The Ditto-Klasse ist ung&uuml;ltig. Bitte &uuml;berpr&uuml;fen.";

$_lang['none'] = "Keine";

$_lang['edit'] = "Bearbeiten";

$_lang['dateFormat'] = "%d.%m.%Y, %H:%M";

// Debug Tab Names

$_lang['info'] = "Info";

$_lang['modx'] = "MODx";

$_lang['fields'] = "Felder";

$_lang['templates'] = "Vorlagen (templates)";

$_lang['filters'] = "Filter";

$_lang['prefetch_data'] = "Vorgeladene Daten";

$_lang['retrieved_data'] = "Abgerufene Daten";

// Debug Text

$_lang['placeholders'] = "Platzhalter";

$_lang['params'] = "Parameter";

$_lang['basic_info'] = "Allgemeine Eigenschaften";

$_lang['document_info'] = "Dokumenteigenschaften";

$_lang['debug'] = "Debug";

$_lang['version'] = "Version";

$_lang['summarize'] = "Zusammenfassen";

$_lang['total'] = "Gesamt";	 

$_lang['sortBy'] = "Sortieren nach";

$_lang['sortDir'] = "Sortierreihenfolge";

$_lang['start'] = "Start";
	 
$_lang['stop'] = "Stop";

$_lang['ditto_IDs'] = "IDs";

$_lang['ditto_IDs_selected'] = "Ausgew&auml;hlte IDs";

$_lang['ditto_IDs_all'] = "Alle IDs";

$_lang['open_dbg_console'] = "Debug-Konsole &ouml;ffnen";

$_lang['save_dbg_console'] = "Debug-Konsole speichern";

?>