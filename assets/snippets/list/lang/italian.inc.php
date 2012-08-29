<?php

/*
 * Title: Language File
 * Purpose:
 *  	Default Italian language file for Ditto
 *  	
 * Note:
 * 		New language keys should added at the bottom of this page
*/

$_lang['language'] = "italian";

$_lang['abbr_lang'] = "it";

$_lang['file_does_not_exist'] = "non esiste. Prego controlla il file.";

$_lang['extender_does_not_exist'] = "extender non esiste. Prego controlla il file.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">di <strong>[+author+]</strong> il [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] either does not contain any placeholders or is an invalid chunk name, code block, or filename. Please check it.</p>";

$_lang['no_documents'] = '<p>Nessun documento trovato.</p>';

$_lang['resource_array_error'] = 'Resource Array Error';
 
$_lang['prev'] = "&lt; Precedente";

$_lang['next'] = "Successivo &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";

$_lang['invalid_class'] = "La classe di Ditto non è valida. Per favore controllala.";

$_lang['none'] = "Nessuno";

$_lang['edit'] = "Modifica";

$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names

$_lang['info'] = "Info";

$_lang['modx'] = "MODx";

$_lang['fields'] = "Campi";

$_lang['templates'] = "Templates";

$_lang['filters'] = "Filtri";

$_lang['prefetch_data'] = "Prefetch Data";

$_lang['retrieved_data'] = "Retreived Data";

// Debug Text

$_lang['placeholders'] = "Placeholders";

$_lang['params'] = "Parameteri";

$_lang['basic_info'] = "Basic Info";

$_lang['document_info'] = "Document Info";

$_lang['debug'] = "Debug";

$_lang['version'] = "Versione";

$_lang['summarize'] = "Summarize";

$_lang['total'] = "Totale";	 

$_lang['sortBy'] = "Ordina per autore";

$_lang['sortDir'] = "Direnzione ordinamento";

$_lang['start'] = "Inizio";
	 
$_lang['stop'] = "Fine";

$_lang['ditto_IDs'] = "IDs";

$_lang['ditto_IDs_selected'] = "Selected IDs";

$_lang['ditto_IDs_all'] = "All IDs";

$_lang['open_dbg_console'] = "Open Debug Console";

$_lang['save_dbg_console'] = "Save Debug Console";

?>