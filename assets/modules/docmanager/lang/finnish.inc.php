<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: doze
 * Language: Finnish
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Dokumenttien hallinta';
$_lang['DM_action_title'] = 'Valitse toiminto';
$_lang['DM_range_title'] = 'Määritä haettavat dokumentit';
$_lang['DM_tree_title'] = 'Valitse dokumenttipuusta';
$_lang['DM_update_title'] = 'Päivitys suoritettu';
$_lang['DM_sort_title'] = 'Valikko järjestyksen muokkaus';

// tabs
$_lang['DM_doc_permissions'] = 'Dokumenttien oikeudet';
$_lang['DM_template_variables'] = 'Sivustopohjamuuttujat';
$_lang['DM_sort_menu'] = 'Järjestä valikon dokumentit';
$_lang['DM_change_template'] = 'Vaihda sivustopohja';
$_lang['DM_publish'] = 'Julkaise/Poista julkaisusta';
$_lang['DM_other'] = 'Muut ominaisuudet';

// buttons
$_lang['DM_close'] = 'Sulje dokumenttien hallinta';
$_lang['DM_cancel'] = 'Takaisin';
$_lang['DM_go'] = 'Suorita';
$_lang['DM_save'] = 'Tallenna';
$_lang['DM_sort_another'] = 'Järjestä toinen';

// templates tab
$_lang['DM_tpl_desc'] = 'Valitse haluttu sivustopohja alla olevasta taulukosta ja määritä dokumenttien ID numerot, joita haluat päivittää. Määritä joko haara dokumenttien ID numeroista tai käytä dokumenttipuu valintaa alla.';
$_lang['DM_tpl_no_templates'] = 'Ei löytynyt yhtään sivustopohjaa';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nimi';
$_lang['DM_tpl_column_description'] = 'Selite';
$_lang['DM_tpl_blank_template'] = 'Tyhjä sivustopohja';
$_lang['DM_tpl_results_message'] = 'Käytä Takaisin painiketta, jos haluat tehdä muita muutoksia. Sivuston välimuisti on tyhjätty automaattisesti.';

// template variables tab
$_lang['DM_tv_desc'] = 'Määritä dokumenttien ID numerot, joita haluat päivittää. Määritä joko haara dokumenttien ID numeroista tai käytä dokumenttipuu valintaa alla. Valitse sitten taulukosta haluttu sivustopohja niin siihen liitetyt sivustopohjamuuttujat ladataan. Syötä haluamasi arvot sivustopohjamuuttujille ja suorita lomake.';
$_lang['DM_tv_template_mismatch'] = 'Tämä dokumentti ei käytä valuttua sivustopohjaa.';
$_lang['DM_tv_doc_not_found'] = 'Tätä dokumenttia ei löytynyt tietokannasta.';
$_lang['DM_tv_no_tv'] = 'Sivustopohjalle ei löytynyt yhtään sivustopohjamuuttujaa.';
$_lang['DM_tv_no_docs'] = 'Ei löytynyt yhtään dokumenttia päivitettäväksi.';
$_lang['DM_tv_no_template_selected'] = 'Sivustopohjaa ei ole valittu.';
$_lang['DM_tv_loading'] = 'Sivustopohjamuuttujia ladataan ..';
$_lang['DM_tv_ignore_tv'] = 'Älä huomioi näitä sivustopohjamuuttujia (pilkulla eroteltu lista):';
$_lang['DM_tv_ajax_insertbutton'] = 'Syötä';

// document permissions tab
$_lang['DM_doc_desc'] = 'Valitse haluamasi dokumenttiryhmät alla olevasta taulukosta sekä haluatko poistaa vai lisätä dokumenttiryhmiä. Määritä sitten dokumenttien ID numerot, joita haluat päivittää. Määritä joko haara dokumenttien ID numeroista tai käytä dokumenttipuu valintaa alla.';
$_lang['DM_doc_no_docs'] = 'Ei löytynyt yhtään dokumenttiryhmää';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nimi';
$_lang['DM_doc_radio_add'] = 'Lisää dokumenttiryhmä';
$_lang['DM_doc_radio_remove'] = 'Poista dokumenttiryhmä';

$_lang['DM_doc_skip_message1'] = 'Dokumentti ID:llä';
$_lang['DM_doc_skip_message2'] = 'kuuluu jo valittuun dokumenttiryhmään (ohitetaan)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Ole hyvä ja klikkaa sivuston runkoon tai kansioon PÄÄDOKUMENTTIPUUSSA, jota haluat järjestää.';
$_lang['DM_sort_updating'] = 'Päivitetään ...';
$_lang['DM_sort_updated'] = 'Päivitetty';
$_lang['DM_sort_nochildren'] = 'Kansiossa ei ole yhtään dokumenttia';
$_lang['DM_sort_noid'] = 'Ei ole valittu yhtään dokumenttia. Ole hyvä ja siirry takaisin sekä valitse dokumentti.';

// other tab
$_lang['DM_other_header'] = 'Selkalaiset dokumenttien asetukset';
$_lang['DM_misc_label'] = 'Käytettävissä olevat asetukset:';
$_lang['DM_misc_desc'] = 'Ole hyvä ja valitse asetus pudotuslistasta sekä sen jälkeen haluttu arvo. Huomioi etä vain yhden asetuksen voi vaihtaa kerrallaan.';

$_lang['DM_other_dropdown_publish'] = 'Julkaise/Poista julkaisusta';
$_lang['DM_other_dropdown_show'] = 'Näytä valikossa/Älä näytä valikossa';
$_lang['DM_other_dropdown_search'] = 'Haettavissa/Ei haettavissa';
$_lang['DM_other_dropdown_cache'] = 'Tallentaminen välimuistiin sallittu/Tallentaminen välimuistiin ei sallittu';
$_lang['DM_other_dropdown_richtext'] = 'Tekstieditori/Ei tekstieditoria';
$_lang['DM_other_dropdown_delete'] = 'Poista/Peruuta poistaminen';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Julkaise';
$_lang['DM_other_publish_radio2'] = 'Poista julkaisusta';
$_lang['DM_other_show_radio1'] = 'Älä näytä valikossa';
$_lang['DM_other_show_radio2'] = 'Näytä valikossa';
$_lang['DM_other_search_radio1'] = 'Haettavissa';
$_lang['DM_other_search_radio2'] = 'Ei haettavissa';
$_lang['DM_other_cache_radio1'] = 'Tallentaminen välimuistiin sallittu';
$_lang['DM_other_cache_radio2'] = 'Tallentaminen välimuistiin ei sallittu';
$_lang['DM_other_richtext_radio1'] = 'Tekstieditori';
$_lang['DM_other_richtext_radio2'] = 'Ei tekstieditoria';
$_lang['DM_other_delete_radio1'] = 'Poista';
$_lang['DM_other_delete_radio2'] = 'Peruuta poistaminen';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Aseta dokumenttien päivämäärät';
$_lang['DM_adjust_dates_desc'] = 'Seuraavat dokumenttien päivämäärät voidaan vaihtaa. Käytä "Näytä kalenteri" toimintoa asettaaksesi päivämäärät.';
$_lang['DM_view_calendar'] = 'Näytä kalenteri';
$_lang['DM_clear_date'] = 'Poista päivämäärä';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Aseta henkilötiedot';
$_lang['DM_adjust_authors_desc'] = 'Käytä pudotuslistaa asettaaksesi uudet henkilötiedot dokumentille.';
$_lang['DM_adjust_authors_createdby'] = 'Luonut:';
$_lang['DM_adjust_authors_editedby'] = 'Muokannut:';
$_lang['DM_adjust_authors_noselection'] = 'Ei valintaa';

// labels
$_lang['DM_date_pubdate'] = 'Julkaisu päivämäärä:';
$_lang['DM_date_unpubdate'] = 'Julkasun päättymispäivämäärä:';
$_lang['DM_date_createdon'] = 'Luotu päivämäärä:';
$_lang['DM_date_editedon'] = 'Muokattu päivämäärä:';
$_lang['DM_date_notset'] = ' (ei asetettu)';
$_lang['DM_date_dateselect_label'] = 'Valitse päivämäärä: ';

// document select section
$_lang['DM_select_submit'] = 'Lähetä';
$_lang['DM_select_range'] = 'Vaihda takaisin dokumenttien ID haaran määritykseen';
$_lang['DM_select_range_text'] = '<p><strong>Avain (jossa n on dokumentin ID numero):</strong><br /><br />
							  n* - Päivitä muutos tähän dokumenttiin sekä sen välittömiin aladokumentteihin<br />
							  n** - Päivitä muutos tähän dokumenttiin ja kaikkiin sen aladokumentteihin<br />
							  n-n2 - Päivitä muutos näiden dokumenttien välillä<br />
							  n - Päivitä muutos yksittäiseen dokumenttiin</p>
							  <p>Esimerkki: 1*,4**,2-20,25 - Tämä päivittää muutoksen dokumenttiin 1 ja sen aladokumentteihin, dokumenttiin 4 ja sen kaikkiin aladokumentteihin, dokumentteihin joiden ID numero on 2-20, sekä dokumenttiin jonka ID numero on 25.</p>';
$_lang['DM_select_tree'] = 'Valitse dokumentit käyttäen dokumenttipuuta';

// process tree/range messages
$_lang['DM_process_noselection'] = 'Valintaa ei tehty. ';
$_lang['DM_process_novalues'] = 'Arvoja ei ole määritetty.';
$_lang['DM_process_limits_error'] = 'Yläarvo vähemmän kuin ala-arvo:';
$_lang['DM_process_invalid_error'] = 'Virheellinen arvo:';
$_lang['DM_process_update_success'] = 'Päivitys suoritettu onnistuneesti.';
$_lang['DM_process_update_error'] = 'Päivitys suoritettu, mutta havaittiin seuraavat virheet:';
$_lang['DM_process_back'] = 'Takaisin';

// manager access logging
$_lang['DM_log_template'] = 'Dokumenttien hallinta moduuli: Sivustopohja vaihdettu.';
$_lang['DM_log_templatevariables'] = 'Dokumenttien hallinta moduuli: Sivustopohjamuuttujat muutettu.';
$_lang['DM_log_docpermissions'] = 'Dokumenttien hallinta moduuli: Dokumenttien oikeudet muutettu.';
$_lang['DM_log_sortmenu'] = 'Dokumenttien hallinta moduuli: Valikon järjestysoperaatio suoritettu.';
$_lang['DM_log_publish'] = 'Dokumenttien hallinta moduuli: Dokumenttien julkaisuasetuksia muutettu.';
$_lang['DM_log_hidemenu'] = 'Dokumenttien hallinta moduuli: Dokumenttien valikon näkyvyysasetuksia muutettu.';
$_lang['DM_log_search'] = 'Dokumenttien hallinta moduuli: Dokumenttien hakuasetuksia muutettu.';
$_lang['DM_log_cache'] = 'Dokumenttien hallinta moduuli: Dokumenttien välimuistiasetuksia muutettu.';
$_lang['DM_log_richtext'] = 'Dokumenttien hallinta moduuli: Dokumenttien tekstieditoriasetuksia muutettu.';
$_lang['DM_log_delete'] = 'Dokumenttien hallinta moduuli: Dokumenttien poistoasetuksia muutettu.';
$_lang['DM_log_dates'] = 'Dokumenttien hallinta moduuli: Dokumenttien päivämääriä muutettu.';
$_lang['DM_log_authors'] = 'Dokumenttien hallinta moduuli: Dokumenttien henkilötietoja muutettu.';
?>
