<?php
/**
 * Title: Language File
 * Purpose: Default Czech language file for Ditto
 * Author: modxcms.cz
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "czech";
$_lang['abbr_lang'] = "cs";
$_lang['file_does_not_exist'] = "neexistuje. Prosím zkontrolujte soubor.";
$_lang['extender_does_not_exist'] = "rozšíření neexistuje. Prosím zkontrolujte to.";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">od <strong>[+author+]</strong> dne [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] neobsahuje žádný Placeholder nebo není názvem Chunku, bloku kódu nebo souboru. Zkontrolujte to.</p>";
$_lang['missing_placeholders_tpl'] = 'One of your Ditto templates are missing placeholders, please check the template below:';
$_lang['no_documents'] = '<p>Dokument nenalezen.</p>';
$_lang['resource_array_error'] = 'Chyba pole zdrojů';
$_lang['prev'] = "&lt; Předchozí";
$_lang['next'] = "Následující &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2011";
$_lang['invalid_class'] = "Ditto class je chybná. Prosím zkontrolujte to.";
$_lang['none'] = "Nic";
$_lang['edit'] = "Upravit";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Info";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Pole";
$_lang['templates'] = "Šablony";
$_lang['filters'] = "Filtry";
$_lang['prefetch_data'] = "Přednačíst Data";
$_lang['retrieved_data'] = "Přijatá Data";

// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Parametery";
$_lang['basic_info'] = "Základní Info";
$_lang['document_info'] = "Info Dokumentu";
$_lang['debug'] = "Debug";
$_lang['version'] = "Verze";
$_lang['summarize'] = "Shrnutí";
$_lang['total'] = "Celkem";
$_lang['sortBy'] = "Řadit dle";
$_lang['sortDir'] = "Směr řazení";
$_lang['start'] = "Začítek";
$_lang['stop'] = "Konec";
$_lang['ditto_IDs'] = "ID";
$_lang['ditto_IDs_selected'] = "Vybraná ID";
$_lang['ditto_IDs_all'] = "Všechna ID";
$_lang['open_dbg_console'] = "Otevřít Debug konzoli";
$_lang['save_dbg_console'] = "Uložit Debug konzoli";
