<?php
/**
 * Title: Language File
 * Purpose: Default Finnish language file for Ditto
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "finnish";
$_lang['abbr_lang'] = "fi";
$_lang['file_does_not_exist'] = "ei löydy. Ole hyvä ja tarkista tiedosto.";
$_lang['extender_does_not_exist'] = "laajennusta (extender) ei löydy. Ole hyvä ja tarkista.";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">Luonut: <strong>[+author+]</strong> - [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] ei joko sisällä yhtään Ditton muuttujaa tai on viallinen palasen nimi, koodi, tai tiedostonimi. Ole hyvä ja tarkista.</p>";
$_lang['missing_placeholders_tpl'] = 'Jostakin Ditto palasestasi puuttuu muuttujia. Ole hyvä ja tarkista alla oleva palanen:';
$_lang['no_documents'] = '<p>Ei löytynyt yhtään dokumenttia.</p>';
$_lang['resource_array_error'] = 'Resurssitaulukko virhe';
$_lang['prev'] = "&lt; Edellinen";
$_lang['next'] = "Seuraava &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2007";
$_lang['invalid_class'] = "Ditto luokka on virheellinen, ole hyvä ja tarkista.";
$_lang['none'] = "Tyhjä";
$_lang['edit'] = "Muokkaa";
$_lang['dateFormat'] = "%d.%m.%Y %H:%M";

// Debug Tab Names
$_lang['info'] = "Tiedot";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Kentät";
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
$_lang['total'] = "Yhteensä";
$_lang['sortBy'] = "Järjestetty";
$_lang['sortDir'] = "Järjestyksen suunta";
$_lang['start'] = "Aloitus (Start)";
$_lang['stop'] = "Lopetus (Stop)";
$_lang['ditto_IDs'] = "ID numerot";
$_lang['ditto_IDs_selected'] = "Valitut ID numerot";
$_lang['ditto_IDs_all'] = "Kaikki ID numerot";
$_lang['open_dbg_console'] = "Avaa vikatietokonsoli";
$_lang['save_dbg_console'] = "Tallenna vikatietokonsoli";
