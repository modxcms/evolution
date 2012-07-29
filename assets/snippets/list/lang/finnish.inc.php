<?php

/*
 * Title: Language File
 * Purpose:
 *  	Default English language file for Ditto
 *  	
 * Note:
 * 		New language keys should added at the bottom of this page
*/

$_lang['language'] = "finnish";

$_lang['abbr_lang'] = "fi";

$_lang['file_does_not_exist'] = "ei l&ouml;ydy. Ole hyv&auml; ja tarkista tiedosto.";

$_lang['extender_does_not_exist'] = "laajennusta (extender) ei l&ouml;ydy. Ole hyv&auml; ja tarkista.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">Luonut: <strong>[+author+]</strong> - [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] ei joko sis&auml;ll&auml; yht&auml;&auml;n Ditton muuttujaa tai on viallinen palasen nimi, koodi, tai tiedostonimi. Ole hyv&auml; ja tarkista.</p>";

$_lang['no_documents'] = '<p>Ei l&ouml;ytynyt yht&auml;&auml;n dokumenttia.</p>';

$_lang['resource_array_error'] = 'Resurssitaulukko virhe';
 
$_lang['prev'] = "&lt; Edellinen";

$_lang['next'] = "Seuraava &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";

$_lang['invalid_class'] = "Ditto luokka on virheellinen, ole hyv&auml; ja tarkista.";

$_lang['none'] = "Tyhj&auml;";

$_lang['edit'] = "Muokkaa";

$_lang['dateFormat'] = "%d.%m.%Y %H:%M";

// Debug Tab Names

$_lang['info'] = "Tiedot";

$_lang['modx'] = "MODx";

$_lang['fields'] = "Kent&auml;t";

$_lang['templates'] = "Sivustopohjat";

$_lang['filters'] = "Filtterit";

$_lang['prefetch_data'] = "Esihaun tulos";

$_lang['retrieved_data'] = "Haun tulos";

// Debug Text

$_lang['placeholders'] = "Muuttujat";

$_lang['params'] = "Parameterit";

$_lang['basic_info'] = "Perustiedot";

$_lang['document_info'] = "Dokumentin tiedot";

$_lang['debug'] = "Vikatiedot";

$_lang['version'] = "Versio";

$_lang['summarize'] = "Yhteenveto";

$_lang['total'] = "Yhteens&auml;";	 

$_lang['sortBy'] = "J&auml;rjestetty";

$_lang['sortDir'] = "J&auml;rjestyksen suunta";

$_lang['start'] = "Aloitus (Start)";
	 
$_lang['stop'] = "Lopetus (Stop)";

$_lang['ditto_IDs'] = "ID numerot";

$_lang['ditto_IDs_selected'] = "Valitut ID numerot";

$_lang['ditto_IDs_all'] = "Kaikki ID numerot";

$_lang['open_dbg_console'] = "Avaa vikatietokonsoli";

$_lang['save_dbg_console'] = "Tallenna vikatietokonsoli";

?>