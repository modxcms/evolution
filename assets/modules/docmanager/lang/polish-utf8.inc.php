<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Łukasz Kowalczyk, www.pixeligence.pl
 * Language: English
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Menedżer dokumentów';
$_lang['DM_action_title'] = 'Wybierz akcję';
$_lang['DM_range_title'] = 'Podaj zakres ID dokumentów';
$_lang['DM_tree_title'] = 'Wybierz dokumenty z drzewa';
$_lang['DM_update_title'] = 'Update zakończony';
$_lang['DM_sort_title'] = 'Edytor indeksów menu';

// tabs
$_lang['DM_doc_permissions'] = 'Uprawnienia dokumentów';
$_lang['DM_template_variables'] = 'Zmienne szablonu';
$_lang['DM_sort_menu'] = 'Sortuj pozycje menu';
$_lang['DM_change_template'] = 'Zmień szablon';
$_lang['DM_publish'] = 'Publikuj/Niepublikuj';
$_lang['DM_other'] = 'Inne właściwości';

// buttons
$_lang['DM_close'] = 'Zamknij Menedżera Dokumentów';
$_lang['DM_cancel'] = 'Powrót';
$_lang['DM_go'] = 'Wykonaj';
$_lang['DM_save'] = 'Zapisz';
$_lang['DM_sort_another'] = 'Sortuj inne';

// templates tab
$_lang['DM_tpl_desc'] = '
Wybierz szablon z poniższej tabeli i podaj ID dokumentów, do których chcesz zastosować zmiany. Możesz to zrobić przez wpisanie zakresu ID lub używając opcji Drzewa poniżej.';
$_lang['DM_tpl_no_templates'] = 'Nie znaleziono szablonów';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nazwa';
$_lang['DM_tpl_column_description'] = 'Opis';
$_lang['DM_tpl_blank_template'] = 'Pusty szablon';
$_lang['DM_tpl_results_message'] = 'Użyj przycisku Powrót jeśli musisz wprowadzić więcej zmian. Cache strony został automatycznie wyczyszczony.';

// template variables tab
$_lang['DM_tv_desc'] = 'Wybierz szablon z poniższej tabeli i podaj ID dokumentów, do których chcesz zastosować zmiany. Możesz to zrobić przez wpisanie zakresu ID lub używając opcji Drzewa poniżej. Potem wybierz szablon z tabeli i przypisane zmienne szablonu zostaną załadowane. Wprowadź pożądane wartości Zmiennych Szablonu i wyślij do przetwarzania.';
$_lang['DM_tv_template_mismatch'] = 'Ten dokument nie używa wybranego szablonu.';
$_lang['DM_tv_doc_not_found'] = 'Ten dokument nie został znaleziony w bazie danych.';
$_lang['DM_tv_no_tv'] = 'Nie znaleziono żadnych Zmiennych Szablonu.';
$_lang['DM_tv_no_docs'] = 'Nie wybrano żadnego dokumentu do zmiany.';
$_lang['DM_tv_no_template_selected'] = 'Żaden szablon nie został wybrany.';
$_lang['DM_tv_loading'] = 'Ładowanie Zmiennych Szablonu...';
$_lang['DM_tv_ignore_tv'] = 'Ignoruj te Zmienne Szablonu (wartości oddziel przecinkiem):';
$_lang['DM_tv_ajax_insertbutton'] = 'Wstaw';

// document permissions tab
$_lang['DM_doc_desc'] = 'Wybierz grupę dokumentów z poniższej tabeli. Następnie podaj ID dokumentów, które chcesz zmienić.	Możesz to zrobić przez wpisanie zakresu ID lub używając opcji Drzewa poniżej.';
$_lang['DM_doc_no_docs'] = 'Nie znaleziono grup dokumentów';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nazwa';
$_lang['DM_doc_radio_add'] = 'Dodaj grupę dokumentów';
$_lang['DM_doc_radio_remove'] = 'Usuń grupę dokumentów';

$_lang['DM_doc_skip_message1'] = 'Dokument z ID';
$_lang['DM_doc_skip_message2'] = 'już należy do wybranej grupy (pomijanie)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Proszę kliknąć korzeń strony albo dokument nadrzędny z głównego drzewa dokumentów, który chcesz posortować.';
$_lang['DM_sort_updating'] = 'Zmieniam...';
$_lang['DM_sort_updated'] = 'Zakończono';
$_lang['DM_sort_nochildren'] = 'Dokument nie ma żadnych dokumentów podrzędnych';
$_lang['DM_sort_noid'] = 'Nie wybrano żadnego dokumentu. Proszę wrócić i wybrać dokument.';

// other tab
$_lang['DM_other_header'] = 'Inne ustawienia dokumentów';
$_lang['DM_misc_label'] = 'Dostępne ustawienia:';
$_lang['DM_misc_desc'] = 'Proszę wybrać ustawienie z listy rozwijalnej, a potem wybrać żądaną opcję. Może zostać zmienione tylko jedno ustawienie naraz.';

$_lang['DM_other_dropdown_publish'] = 'Publikuj/Niepublikuj';
$_lang['DM_other_dropdown_show'] = 'Ukryj/Pokaż w menu';
$_lang['DM_other_dropdown_search'] = 'Przeszukiwalny/Nieprzeszukiwalny';
$_lang['DM_other_dropdown_cache'] = 'Cache-owalny/Nie-cache-owalny';
$_lang['DM_other_dropdown_richtext'] = 'Edytor/Brak edytora';
$_lang['DM_other_dropdown_delete'] = 'Usunięty/Nie usunięty';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publikuj';
$_lang['DM_other_publish_radio2'] = 'Niepublikuj';
$_lang['DM_other_show_radio1'] = 'Schowaj w menu';
$_lang['DM_other_show_radio2'] = 'Pokaż w menu';
$_lang['DM_other_search_radio1'] = 'Przeszukiwalny';
$_lang['DM_other_search_radio2'] = 'Nieprzeszukiwalny';
$_lang['DM_other_cache_radio1'] = 'Cache-owalny';
$_lang['DM_other_cache_radio2'] = 'Nie-cache-owalny';
$_lang['DM_other_richtext_radio1'] = 'Edytor WYSIWYG';
$_lang['DM_other_richtext_radio2'] = 'Brak edytora';
$_lang['DM_other_delete_radio1'] = 'Usunięty';
$_lang['DM_other_delete_radio2'] = 'Nie usunięty';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Ustaw daty dokumentów';
$_lang['DM_adjust_dates_desc'] = 'Wszystkie poniższe ustawienia dat dokumentów mogą zostać zmienione. Użyj kalendarza, aby ustawić daty.';
$_lang['DM_view_calendar'] = 'Pokaż kalendarz';
$_lang['DM_clear_date'] = 'Wyczyść datę';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Ustaw autorów';
$_lang['DM_adjust_authors_desc'] = 'Użyj listy rozwijalnej aby przypisać nowych autorów do dokumentu.';
$_lang['DM_adjust_authors_createdby'] = 'Stworzony przez:';
$_lang['DM_adjust_authors_editedby'] = 'Edytowany przez:';
$_lang['DM_adjust_authors_noselection'] = 'Bez zmian';

// labels
$_lang['DM_date_pubdate'] = 'Data publikacji:';
$_lang['DM_date_unpubdate'] = 'Data przerwania publikacji:';
$_lang['DM_date_createdon'] = 'Data utworzenia:';
$_lang['DM_date_editedon'] = 'Data edycji:';
$_lang['DM_date_notset'] = ' (nie ustawione)';
$_lang['DM_date_dateselect_label'] = 'Wybierz datę: ';

// document select section
$_lang['DM_select_submit'] = 'Wyślij';
$_lang['DM_select_range'] = 'Powrót do wybierania ID dokumentów';
$_lang['DM_select_range_text'] = '<p><strong>Klucz (gdzie n jest numerem ID dokumentu):</strong><br /><br />
							  n* - Zmień ustawienia tego dokumentu i dokumentów bezpośrednio podrzędnych<br />
							  n** - Zmień ustawienia dla tego dokumentu i wszystkich dokumentów podrzędnych<br />
							  n-n2 - Zmień ustawienia dla dokumentów z podanego zakresu<br />
							  n - Zmień ustawienia dla pojedyńczego dokumentu</p>
							  <p>Przykład: 1*,4**,2-20,25 - To zmieni ustawienia dla dokumentu nr 1 i jego dokumentów podrzędnych, dokumentu nr 4 i wszystkich jego dokumentów podrzędnych, dokumentu od 2 do 20 oraz dla dokumentu nr 25.</p>';
$_lang['DM_select_tree'] = 'Przejrzyj i wybierz dokumenty używając drzewa dokumentów';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Nic nie wybrano. ';
$_lang['DM_process_novalues'] = 'Nie podano żadnych wartości.';
$_lang['DM_process_limits_error'] = 'Zakres górny mniejszy niż zakres dolny:';
$_lang['DM_process_invalid_error'] = 'Nieprawidłowe wartości:';
$_lang['DM_process_update_success'] = 'Zmiany zostały dokonane bez błędów.';
$_lang['DM_process_update_error'] = 'Zmiany zostały dokonane, ale wystąpiły błędy:';
$_lang['DM_process_back'] = 'Powrót';

// manager access logging
$_lang['DM_log_template'] = 'Menedżer dokumentów: szablony zmienione';
$_lang['DM_log_templatevariables'] = 'Menedżer dokumentów: zmienne szablonu zmienione.';
$_lang['DM_log_docpermissions'] = 'Menedżer dokumentów: uprawnienia zmienione.';
$_lang['DM_log_sortmenu'] = 'Menedżer dokumentów: indeksowanie menu zakończone.';
$_lang['DM_log_publish'] = 'Menedżer dokumentów: ustawienia publikowania zmienione.';
$_lang['DM_log_hidemenu'] = 'Menedżer dokumentów: ustawienia pokazywania w menu zmienione.';
$_lang['DM_log_search'] = 'Menedżer dokumentów: ustawienia przeszukiwania zmienione.';
$_lang['DM_log_cache'] = 'Menedżer dokumentów: ustawienia cachowanie zmienione.';
$_lang['DM_log_richtext'] = 'Menedżer dokumentów: ustawienia użycia edytora zmienione.';
$_lang['DM_log_delete'] = 'Menedżer dokumentów: ustawienia usuwania zmienione.';
$_lang['DM_log_dates'] = 'Menedżer dokumentów: daty dokumentów zmienione.';
$_lang['DM_log_authors'] = 'Menedżer dokumentów: ustawienia autorów zmienione.';
?>
