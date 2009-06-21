<?php
/**
 * Document Manager Module - polish.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: £ukasz Kowalczyk // www.pixeligence.pl
 * For: MODx CMS (www.modxcms.com)
 * Date:10/12/2006 Version: 0.9.5
 * 
 */
 
//-- POLISH LANGUAGE FILE
 
//-- titles
$_lang['DM_module_title'] = 'Mened¿er dokumentów';
$_lang['DM_action_title'] = 'Wybierz akcjê';
$_lang['DM_range_title'] = 'Podaj zakres ID dokumentów';
$_lang['DM_tree_title'] = 'Wybierz dokumenty z drzewa';
$_lang['DM_update_title'] = 'Update zakoñczony';
$_lang['DM_sort_title'] = 'Edytor indeksów menu';

//-- tabs
$_lang['DM_doc_permissions'] = 'Uprawnienia dokumentów';
$_lang['DM_template_variables'] = 'Zmienne szablonu';
$_lang['DM_sort_menu'] = 'Sortuj pozycje menu';
$_lang['DM_change_template'] = 'Zmieñ szablon';
$_lang['DM_publish'] = 'Publikuj/Niepublikuj';
$_lang['DM_other'] = 'Inne w³a¶ciwo¶ci';
 
//-- buttons
$_lang['DM_close'] = 'Zamknij Mened¿era Dokumentów';
$_lang['DM_cancel'] = 'Powrót';
$_lang['DM_go'] = 'Wykonaj';
$_lang['DM_save'] = 'Zapisz';
$_lang['DM_sort_another'] = 'Sortuj inne';

//-- templates tab
$_lang['DM_tpl_desc'] = '
Wybierz szablon z poni¿szej tabeli i podaj ID dokumentów, do których chcesz zastosowaæ zmiany. Mo¿esz to zrobiæ przez wpisanie zakresu ID lub u¿ywaj±c opcji Drzewa poni¿ej.';
$_lang['DM_tpl_no_templates'] = 'Nie znaleziono szablonów';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nazwa';
$_lang['DM_tpl_column_description'] ='Opis';
$_lang['DM_tpl_blank_template'] = 'Pusty szablon';

$_lang['DM_tpl_results_message']= 'U¿yj przycisku Powrót je¶li musisz wprowadziæ wiêcej zmian. Cache strony zosta³ automatycznie wyczyszczony.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Wybierz szablon z poni¿szej tabeli i podaj ID dokumentów, do których chcesz zastosowaæ zmiany. Mo¿esz to zrobiæ przez wpisanie zakresu ID lub u¿ywaj±c opcji Drzewa poni¿ej. Potem wybierz szablon z tabeli i przypisane zmienne szablonu zostan± za³adowane. Wprowad¼ po¿±dane warto¶ci Zmiennych Szablonu i wy¶lij do przetwarzania.';
$_lang['DM_tv_template_mismatch'] = 'Ten dokument nie u¿ywa wybranego szablonu.';
$_lang['DM_tv_doc_not_found'] = 'Ten dokument nie zosta³ znaleziony w bazie danych.';
$_lang['DM_tv_no_tv'] = 'Nie znaleziono ¿adnych Zmiennych Szablonu.';
$_lang['DM_tv_no_docs'] = 'Nie wybrano ¿adnego dokumentu do zmiany.';
$_lang['DM_tv_no_template_selected'] = '¯aden szablon nie zosta³ wybrany.';
$_lang['DM_tv_loading'] = '£adowanie Zmiennych Szablonu...';
$_lang['DM_tv_ignore_tv'] = 'Ignoruj te Zmienne Szablonu (warto¶ci oddziel przecinkiem):';
$_lang['DM_tv_ajax_insertbutton'] = 'Wstaw';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'Wybierz grupê dokumentów z poni¿szej tabeli. Nastêpnie podaj ID dokumentów, które chcesz zmieniæ.	Mo¿esz to zrobiæ przez wpisanie zakresu ID lub u¿ywaj±c opcji Drzewa poni¿ej.';
$_lang['DM_doc_no_docs'] = 'Nie znaleziono grup dokumentów';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nazwa';
$_lang['DM_doc_radio_add'] = 'Dodaj grupê dokumentów';
$_lang['DM_doc_radio_remove'] = 'Usuñ grupê dokumentów';

$_lang['DM_doc_skip_message1'] = 'Dokument z ID';
$_lang['DM_doc_skip_message2'] = 'ju¿ nale¿y do wybranej grupy (pomijanie)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Proszê klikn±æ korzeñ strony albo dokument nadrzêdny z g³ównego drzewa dokumentów, który chcesz posortowaæ.'; 
$_lang['DM_sort_updating'] = 'Zmieniam...';
$_lang['DM_sort_updated'] = 'Zakoñczono';
$_lang['DM_sort_nochildren'] = 'Dokument nie ma ¿adnych dokumentów podrzêdnych';
$_lang['DM_sort_noid']='Nie wybrano ¿adnego dokumentu. Proszê wróciæ i wybraæ dokument.';

//-- other tab
$_lang['DM_other_header'] = 'Inne ustawienia dokumentów';
$_lang['DM_misc_label'] = 'Dostêpne ustawienia:';
$_lang['DM_misc_desc'] = 'Proszê wybraæ ustawienie z listy rozwijalnej, a potem wybraæ ¿±dan± opcjê. Mo¿e zostaæ zmienione tylko jedno ustawienie naraz.';

$_lang['DM_other_dropdown_publish'] = 'Publikuj/Niepublikuj';
$_lang['DM_other_dropdown_show'] = 'Ukryj/Poka¿ w menu';
$_lang['DM_other_dropdown_search'] = 'Przeszukiwalny/Nieprzeszukiwalny';
$_lang['DM_other_dropdown_cache'] = 'Cache-owalny/Nie-cache-owalny';
$_lang['DM_other_dropdown_richtext'] = 'Edytor/Brak edytora';
$_lang['DM_other_dropdown_delete'] = 'Usuniêty/Nie usuniêty';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Publikuj'; 
$_lang['DM_other_publish_radio2'] = 'Niepublikuj';
$_lang['DM_other_show_radio1'] = 'Schowaj w menu'; 
$_lang['DM_other_show_radio2'] = 'Poka¿ w menu';
$_lang['DM_other_search_radio1'] = 'Przeszukiwalny'; 
$_lang['DM_other_search_radio2'] = 'Nieprzeszukiwalny';
$_lang['DM_other_cache_radio1'] = 'Cache-owalny'; 
$_lang['DM_other_cache_radio2'] = 'Nie-cache-owalny';
$_lang['DM_other_richtext_radio1'] = 'Edytor WYSIWYG'; 
$_lang['DM_other_richtext_radio2'] = 'Brak edytora';
$_lang['DM_other_delete_radio1'] = 'Usuniêty'; 
$_lang['DM_other_delete_radio2'] = 'Nie usuniêty';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'Ustaw daty dokumentów';
$_lang['DM_adjust_dates_desc'] = 'Wszystkie poni¿sze ustawienia dat dokumentów mog± zostaæ zmienione. U¿yj kalendarza, aby ustawiæ daty.';
$_lang['DM_view_calendar'] = 'Poka¿ kalendarz';
$_lang['DM_clear_date'] = 'Wyczy¶æ datê';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'Ustaw autorów';
$_lang['DM_adjust_authors_desc'] = 'U¿yj listy rozwijalnej aby przypisaæ nowych autorów do dokumentu.';
$_lang['DM_adjust_authors_createdby'] = 'Stworzony przez:';
$_lang['DM_adjust_authors_editedby'] = 'Edytowany przez:';
$_lang['DM_adjust_authors_noselection'] = 'Bez zmian';

 //-- labels
$_lang['DM_date_pubdate'] = 'Data publikacji:';
$_lang['DM_date_unpubdate'] = 'Data przerwania publikacji:';
$_lang['DM_date_createdon'] = 'Data utworzenia:';
$_lang['DM_date_editedon'] = 'Data edycji:';
//$_lang['DM_date_deletedon'] = 'Deleted On Date';

$_lang['DM_date_notset'] = ' (nie ustawione)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'Wybierz datê: ';

//-- document select section
$_lang['DM_select_submit'] = 'Wy¶lij';
$_lang['DM_select_range'] = 'Powrót do wybierania ID dokumentów';
$_lang['DM_select_range_text'] = '<p><strong>Klucz (gdzie n jest numerem ID dokumentu):</strong><br /><br />
							  n* - Zmieñ ustawienia tego dokumentu i dokumentów bezpo¶rednio podrzêdnych<br /> 
							  n** - Zmieñ ustawienia dla tego dokumentu i wszystkich dokumentów podrzêdnych<br /> 
							  n-n2 - Zmieñ ustawienia dla dokumentów z podanego zakresu<br /> 
							  n - Zmieñ ustawienia dla pojedyñczego dokumentu</p> 
							  <p>Przyk³ad: 1*,4**,2-20,25 - To zmieni ustawienia dla dokumentu nr 1 i jego dokumentów podrzêdnych, dokumentu nr 4 i wszystkich jego dokumentów podrzêdnych, dokumentu od 2 do 20 oraz dla dokumentu nr 25.</p>';
$_lang['DM_select_tree'] ='Przejrzyj i wybierz dokumenty u¿ywaj±c drzewa dokumentów';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'Nic nie wybrano. ';
$_lang['DM_process_novalues'] = 'Nie podano ¿adnych warto¶ci.';
$_lang['DM_process_limits_error'] = 'Zakres górny mniejszy ni¿ zakres dolny:';
$_lang['DM_process_invalid_error'] = 'Nieprawid³owe warto¶ci:';
$_lang['DM_process_update_success'] = 'Zmiany zosta³y dokonane bez b³êdów.';
$_lang['DM_process_update_error'] = 'Zmiany zosta³y dokonane, ale wyst±pi³y b³êdy:';
$_lang['DM_process_back'] = 'Powrót';

//-- manager access logging
$_lang['DM_log_template'] = 'Mened¿er dokumentów: szablony zmienione';
$_lang['DM_log_templatevariables'] = 'Mened¿er dokumentów: zmienne szablonu zmienione.';
$_lang['DM_log_docpermissions'] ='Mened¿er dokumentów: uprawnienia zmienione.';
$_lang['DM_log_sortmenu']='Mened¿er dokumentów: indeksowanie menu zakoñczone.';
$_lang['DM_log_publish']='Mened¿er dokumentów: ustawienia publikowania zmienione.';
$_lang['DM_log_hidemenu']='Mened¿er dokumentów: ustawienia pokazywania w menu zmienione.';
$_lang['DM_log_search']='Mened¿er dokumentów: ustawienia przeszukiwania zmienione.';
$_lang['DM_log_cache']='Mened¿er dokumentów: ustawienia cachowanie zmienione.';
$_lang['DM_log_richtext']='Mened¿er dokumentów: ustawienia u¿ycia edytora zmienione.';
$_lang['DM_log_delete']='Mened¿er dokumentów: ustawienia usuwania zmienione.';
$_lang['DM_log_dates']='Mened¿er dokumentów: daty dokumentów zmienione.';
$_lang['DM_log_authors']='Mened¿er dokumentów: ustawienia autorów zmienione.';

?>
