<?php
/**
 * Title: Language File
 * Purpose: Default Italian language file for Ditto
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "italian";
$_lang['abbr_lang'] = "it";
$_lang['file_does_not_exist'] = "non esiste. Vi preghiamo di controllare il file.";
$_lang['extender_does_not_exist'] = "extender non esiste. Vi preghiamo di controllare il file.";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">di <strong>[+author+]</strong> il [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] potrebbe non contenere placeholders oppure non essere un nome valido di chunk o di file. Vi preghiamo di verificare.</p>";
$_lang['missing_placeholders_tpl'] = 'Alcuni dei template non hanno tutti i placeholders, verificate i seguenti template:';
$_lang['no_documents'] = '<p>Nessun documento trovato.</p>';
$_lang['resource_array_error'] = 'Errore nell\'Array della Risorsa';
$_lang['prev'] = "&lt; Precedente";
$_lang['next'] = "Successivo &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2006";
$_lang['invalid_class'] = "La classe Ditto non è valida. Vi preghiamo di verificare.";
$_lang['none'] = "Nessuno";
$_lang['edit'] = "Modifica";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Informazioni";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Campi";
$_lang['templates'] = "Templates";
$_lang['filters'] = "Filtri";
$_lang['prefetch_data'] = "Dati Precaricati";
$_lang['retrieved_data'] = "Dati Caricati";

// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Parametri";
$_lang['basic_info'] = "Informazioni di base";
$_lang['document_info'] = "Informazioni sul Documento";
$_lang['debug'] = "Debug";
$_lang['version'] = "Versione";
$_lang['summarize'] = "Riepiloga";
$_lang['total'] = "Totale";
$_lang['sortBy'] = "Ordina per";
$_lang['sortDir'] = "Direzione ordinamento";
$_lang['start'] = "Inizio";
$_lang['stop'] = "Fine";
$_lang['ditto_IDs'] = "IDs";
$_lang['ditto_IDs_selected'] = "IDs Selezionate";
$_lang['ditto_IDs_all'] = "Tutte le IDs";
$_lang['open_dbg_console'] = "Apri la Console di Debug";
$_lang['save_dbg_console'] = "Salva la Console di Debug";
