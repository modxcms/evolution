<?php
/**
 * Title: Language File
 * Purpose: Default Polish language file for Ditto
 * Author: Witek Galecki 
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "polish";
$_lang['abbr_lang'] = "pl";
$_lang['file_does_not_exist'] = "nie istnieje.";
$_lang['extender_does_not_exist'] = "extender nie istnieje.";
$_lang['default_template'] = '
<div class="ditto_item" id="ditto_item_[+id+]">
<h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
<div class="ditto_documentInfo">dodane [+date+] przez <strong>[+author+]</strong></div>
<div class="ditto_introText">[+introtext+]</div>
</div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] albo nie zawiera żadnych placeholderów, albo nie jest prawidłową nazwą chunka, blokiem kodu lub nazwą pliku.</p>";
$_lang['missing_placeholders_tpl'] = 'W jednym z szablonów Ditto występują brakujące placeholdery, przejrzyj poniższy szablon:';
$_lang['no_documents'] = '<p>Brak dokumentów do wyświetlenia.</p>';
$_lang['resource_array_error'] = 'Błąd Tablicy Zasobów';
$_lang['prev'] = "&lt; Poprzednie";
$_lang['next'] = "Następne &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2006";
$_lang['invalid_class'] = "Klasa Ditto jest nieprawidłowa.";
$_lang['none'] = "Brak";
$_lang['edit'] = "Edytuj";
$_lang['dateFormat'] = "%d.%b.%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Info";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Pola";
$_lang['templates'] = "Szablony";
$_lang['filters'] = "Filtry";
$_lang['prefetch_data'] = "Wstępnie pobierz dane";
$_lang['retrieved_data'] = "Pobrane dane";

// Debug Text
$_lang['placeholders'] = "Placeholdery";
$_lang['params'] = "Parametry";
$_lang['basic_info'] = "Podstawowe informacje";
$_lang['document_info'] = "Informacje o dokumencie";
$_lang['debug'] = "Debug";
$_lang['version'] = "Wersja";
$_lang['summarize'] = "Podsumuj";
$_lang['total'] = "W sumie";
$_lang['sortBy'] = "Sortuj po";
$_lang['sortDir'] = "Kierunek sortowania";
$_lang['start'] = "Początek";
$_lang['stop'] = "Zatrzymaj";
$_lang['ditto_IDs'] = "Numery ID";
$_lang['ditto_IDs_selected'] = "Wybrane numery ID";
$_lang['ditto_IDs_all'] = "Wszystkie numery ID";
$_lang['open_dbg_console'] = "Otwórz konsolę debugowania";
$_lang['save_dbg_console'] = "Zapisz konsolę debugowania";
