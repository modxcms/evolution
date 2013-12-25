<?php
/**
 * Document Manager Module - polish.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: �ukasz Kowalczyk // www.pixeligence.pl
 * For: MODX CMS (www.modx.com)
 * Date:10/12/2006 Version: 0.9.5
 * 
 */
 
//-- POLISH LANGUAGE FILE
 
//-- titles
$_lang['DM_module_title'] = 'Mened�er dokument�w';
$_lang['DM_action_title'] = 'Wybierz akcj�';
$_lang['DM_range_title'] = 'Podaj zakres ID dokument�w';
$_lang['DM_tree_title'] = 'Wybierz dokumenty z drzewa';
$_lang['DM_update_title'] = 'Update zako�czony';
$_lang['DM_sort_title'] = 'Edytor indeks�w menu';

//-- tabs
$_lang['DM_doc_permissions'] = 'Uprawnienia dokument�w';
$_lang['DM_template_variables'] = 'Zmienne szablonu';
$_lang['DM_sort_menu'] = 'Sortuj pozycje menu';
$_lang['DM_change_template'] = 'Zmie� szablon';
$_lang['DM_publish'] = 'Publikuj/Niepublikuj';
$_lang['DM_other'] = 'Inne w�a�ciwo�ci';
 
//-- buttons
$_lang['DM_close'] = 'Zamknij Mened�era Dokument�w';
$_lang['DM_cancel'] = 'Powr�t';
$_lang['DM_go'] = 'Wykonaj';
$_lang['DM_save'] = 'Zapisz';
$_lang['DM_sort_another'] = 'Sortuj inne';

//-- templates tab
$_lang['DM_tpl_desc'] = '
Wybierz szablon z poni�szej tabeli i podaj ID dokument�w, do kt�rych chcesz zastosowa� zmiany. Mo�esz to zrobi� przez wpisanie zakresu ID lub u�ywaj�c opcji Drzewa poni�ej.';
$_lang['DM_tpl_no_templates'] = 'Nie znaleziono szablon�w';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nazwa';
$_lang['DM_tpl_column_description'] ='Opis';
$_lang['DM_tpl_blank_template'] = 'Pusty szablon';

$_lang['DM_tpl_results_message']= 'U�yj przycisku Powr�t je�li musisz wprowadzi� wi�cej zmian. Cache strony zosta� automatycznie wyczyszczony.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'Wybierz szablon z poni�szej tabeli i podaj ID dokument�w, do kt�rych chcesz zastosowa� zmiany. Mo�esz to zrobi� przez wpisanie zakresu ID lub u�ywaj�c opcji Drzewa poni�ej. Potem wybierz szablon z tabeli i przypisane zmienne szablonu zostan� za�adowane. Wprowad� po��dane warto�ci Zmiennych Szablonu i wy�lij do przetwarzania.';
$_lang['DM_tv_template_mismatch'] = 'Ten dokument nie u�ywa wybranego szablonu.';
$_lang['DM_tv_doc_not_found'] = 'Ten dokument nie zosta� znaleziony w bazie danych.';
$_lang['DM_tv_no_tv'] = 'Nie znaleziono �adnych Zmiennych Szablonu.';
$_lang['DM_tv_no_docs'] = 'Nie wybrano �adnego dokumentu do zmiany.';
$_lang['DM_tv_no_template_selected'] = '�aden szablon nie zosta� wybrany.';
$_lang['DM_tv_loading'] = '�adowanie Zmiennych Szablonu...';
$_lang['DM_tv_ignore_tv'] = 'Ignoruj te Zmienne Szablonu (warto�ci oddziel przecinkiem):';
$_lang['DM_tv_ajax_insertbutton'] = 'Wstaw';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'Wybierz grup� dokument�w z poni�szej tabeli. Nast�pnie podaj ID dokument�w, kt�re chcesz zmieni�.	Mo�esz to zrobi� przez wpisanie zakresu ID lub u�ywaj�c opcji Drzewa poni�ej.';
$_lang['DM_doc_no_docs'] = 'Nie znaleziono grup dokument�w';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nazwa';
$_lang['DM_doc_radio_add'] = 'Dodaj grup� dokument�w';
$_lang['DM_doc_radio_remove'] = 'Usu� grup� dokument�w';

$_lang['DM_doc_skip_message1'] = 'Dokument z ID';
$_lang['DM_doc_skip_message2'] = 'ju� nale�y do wybranej grupy (pomijanie)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'Prosz� klikn�� korze� strony albo dokument nadrz�dny z g��wnego drzewa dokument�w, kt�ry chcesz posortowa�.'; 
$_lang['DM_sort_updating'] = 'Zmieniam...';
$_lang['DM_sort_updated'] = 'Zako�czono';
$_lang['DM_sort_nochildren'] = 'Dokument nie ma �adnych dokument�w podrz�dnych';
$_lang['DM_sort_noid']='Nie wybrano �adnego dokumentu. Prosz� wr�ci� i wybra� dokument.';

//-- other tab
$_lang['DM_other_header'] = 'Inne ustawienia dokument�w';
$_lang['DM_misc_label'] = 'Dost�pne ustawienia:';
$_lang['DM_misc_desc'] = 'Prosz� wybra� ustawienie z listy rozwijalnej, a potem wybra� ��dan� opcj�. Mo�e zosta� zmienione tylko jedno ustawienie naraz.';

$_lang['DM_other_dropdown_publish'] = 'Publikuj/Niepublikuj';
$_lang['DM_other_dropdown_show'] = 'Ukryj/Poka� w menu';
$_lang['DM_other_dropdown_search'] = 'Przeszukiwalny/Nieprzeszukiwalny';
$_lang['DM_other_dropdown_cache'] = 'Cache-owalny/Nie-cache-owalny';
$_lang['DM_other_dropdown_richtext'] = 'Edytor/Brak edytora';
$_lang['DM_other_dropdown_delete'] = 'Usuni�ty/Nie usuni�ty';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'Publikuj'; 
$_lang['DM_other_publish_radio2'] = 'Niepublikuj';
$_lang['DM_other_show_radio1'] = 'Schowaj w menu'; 
$_lang['DM_other_show_radio2'] = 'Poka� w menu';
$_lang['DM_other_search_radio1'] = 'Przeszukiwalny'; 
$_lang['DM_other_search_radio2'] = 'Nieprzeszukiwalny';
$_lang['DM_other_cache_radio1'] = 'Cache-owalny'; 
$_lang['DM_other_cache_radio2'] = 'Nie-cache-owalny';
$_lang['DM_other_richtext_radio1'] = 'Edytor WYSIWYG'; 
$_lang['DM_other_richtext_radio2'] = 'Brak edytora';
$_lang['DM_other_delete_radio1'] = 'Usuni�ty'; 
$_lang['DM_other_delete_radio2'] = 'Nie usuni�ty';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'Ustaw daty dokument�w';
$_lang['DM_adjust_dates_desc'] = 'Wszystkie poni�sze ustawienia dat dokument�w mog� zosta� zmienione. U�yj kalendarza, aby ustawi� daty.';
$_lang['DM_view_calendar'] = 'Poka� kalendarz';
$_lang['DM_clear_date'] = 'Wyczy�� dat�';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'Ustaw autor�w';
$_lang['DM_adjust_authors_desc'] = 'U�yj listy rozwijalnej aby przypisa� nowych autor�w do dokumentu.';
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
$_lang['DM_date_dateselect_label'] = 'Wybierz dat�: ';

//-- document select section
$_lang['DM_select_submit'] = 'Wy�lij';
$_lang['DM_select_range'] = 'Powr�t do wybierania ID dokument�w';
$_lang['DM_select_range_text'] = '<p><strong>Klucz (gdzie n jest numerem ID dokumentu):</strong><br /><br />
							  n* - Zmie� ustawienia tego dokumentu i dokument�w bezpo�rednio podrz�dnych<br /> 
							  n** - Zmie� ustawienia dla tego dokumentu i wszystkich dokument�w podrz�dnych<br /> 
							  n-n2 - Zmie� ustawienia dla dokument�w z podanego zakresu<br /> 
							  n - Zmie� ustawienia dla pojedy�czego dokumentu</p> 
							  <p>Przyk�ad: 1*,4**,2-20,25 - To zmieni ustawienia dla dokumentu nr 1 i jego dokument�w podrz�dnych, dokumentu nr 4 i wszystkich jego dokument�w podrz�dnych, dokumentu od 2 do 20 oraz dla dokumentu nr 25.</p>';
$_lang['DM_select_tree'] ='Przejrzyj i wybierz dokumenty u�ywaj�c drzewa dokument�w';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'Nic nie wybrano. ';
$_lang['DM_process_novalues'] = 'Nie podano �adnych warto�ci.';
$_lang['DM_process_limits_error'] = 'Zakres g�rny mniejszy ni� zakres dolny:';
$_lang['DM_process_invalid_error'] = 'Nieprawid�owe warto�ci:';
$_lang['DM_process_update_success'] = 'Zmiany zosta�y dokonane bez b��d�w.';
$_lang['DM_process_update_error'] = 'Zmiany zosta�y dokonane, ale wyst�pi�y b��dy:';
$_lang['DM_process_back'] = 'Powr�t';

//-- manager access logging
$_lang['DM_log_template'] = 'Mened�er dokument�w: szablony zmienione';
$_lang['DM_log_templatevariables'] = 'Mened�er dokument�w: zmienne szablonu zmienione.';
$_lang['DM_log_docpermissions'] ='Mened�er dokument�w: uprawnienia zmienione.';
$_lang['DM_log_sortmenu']='Mened�er dokument�w: indeksowanie menu zako�czone.';
$_lang['DM_log_publish']='Mened�er dokument�w: ustawienia publikowania zmienione.';
$_lang['DM_log_hidemenu']='Mened�er dokument�w: ustawienia pokazywania w menu zmienione.';
$_lang['DM_log_search']='Mened�er dokument�w: ustawienia przeszukiwania zmienione.';
$_lang['DM_log_cache']='Mened�er dokument�w: ustawienia cachowanie zmienione.';
$_lang['DM_log_richtext']='Mened�er dokument�w: ustawienia u�ycia edytora zmienione.';
$_lang['DM_log_delete']='Mened�er dokument�w: ustawienia usuwania zmienione.';
$_lang['DM_log_dates']='Mened�er dokument�w: daty dokument�w zmienione.';
$_lang['DM_log_authors']='Mened�er dokument�w: ustawienia autor�w zmienione.';

?>
