<?php
/**
 * MODx language File
 *
 * @author davaeron
 * @package MODx installer for 0.9.6.2
 * @revision 3729
 *
 * Filename:       /install/lang/norwegian/norwegian.inc.php
 * Language:       Norsk
 * Encoding:       utf-8
 *
 * Translation: Bjørn Erik Sandbakk (Sylvaticus)
 * Date: 2008-05-22
 */

$_lang['license'] = '<p class="title">MODx Lisensavtale</p>
    <hr style="text-align:left;height:1px;width:100%;" />
    <h4>Du må godkjenne lisensen før installasjonen kan fortsette.</h4>
    <p>Bruk av dette programmet kommer inn under GPL-lisensen. For å hjelpe deg å forstå hva GPL-lisensen er og hvordan den påvirker dine muligheter til å bruke dette programmet, har vi gjort følgende sammenfatning.</p>
    <h4>GNU General Public License (GPL) er en lisens for Fri programvare.</h4>
    <p>Som alle lisenser for Fri programvare gir den deg tilgang til følgende fire friheter:</p>
    <ul>
        <li>Frihet til å bruke programmet til alle formål.</li>
        <li>Frihet til å studere hvordan programmet fungerer og tilpasse det etter dine ønsker.</li>
        <li>Frihet til å distribuere kopier så du kan hjelpe din nabo.</li>
        <li>Frihet til å forbedre programmet og publisere dine forbedringer til almennheten, slik at hele samfunnet kan dra nyttet av dem.</li>
    </ul>
    <p>Du kan benytte ovenforstående spesifiserte friheter forutsatt at du følger de uttrykkelige krav som lisensen uttaler. Kravene er i hovedsak:</p>
    <ul>
        <li>Du må på enhver kopi du distribuerer, tydelig og på forståelig vis publisere en melding om copyright og en garantifraskrvning, beholde alle meldinger som referer til den her lisensen og fravær av garanti, samt gi hver mottager av programmet en kopi av GNU GPL-lisensen sammen med programmet. Alle oversettelser av GNU GPL-lisensen må etterfølges av originalen.</li>

        <li>Om du modifiserer din kopi eller kopier av programmet eller deler av det, eller utvikler et program basert på det, kan du distribuere det resulterende arbeidet forutsatt at du gjør det under GNU GPL-lisensen. Alle oversettelser av GNU GPL-lisensen må etterfølges av originalen.</li>

        <li>Om du kopierer eller distrinuerer programmet, må det etterfølges av den motsvarende maskinlesbare kildekoden eller et skriftlig tilbud, gyldig minst tre år, om å utlevere den motsvarende maskinlesbare kildekoden.</li>

        <li>Ovenforstående krav kan heves om du gis tillatelse av innehaveren av copyrighten.</li>

        <li>Din rett til å sitere samt endre andre rettigheter påvirkes ikke av ovenforstående.</li>
    </ul>
    <p>Ovenforstående er en sammenfattning av GNU GPL-lisensen. Gjennom å fortsette godkjenner du GNU GPL-licensen - ikke ovenforstående. Ovenforstående er en enkel sammenfattning og dens korrekthet kan ikke garanteres. Det oppfordres sterkt at du leser den fullstendige <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL-licensen</a> før du fortsetter. Den finnes også i filen license.txt som distribueres sammen med det her programmet.';
$_lang["encoding"] = 'utf-8';	//charset encoding for html header
$_lang["modx_install"] = 'MODx &raquo; Installasjon';
$_lang["loading"] = 'Henter...';
$_lang["Begin"] = 'Start';
$_lang["status_connecting"] = ' Tilkobling til vertdatamaskin: ';
$_lang["status_failed"] = 'mislyktes!';
$_lang["status_passed"] = 'godkjent - databasen valgt';
$_lang["status_passed_server"] = 'godkjent - kollasjoneringer er nå tilgjengelig';
$_lang["status_passed_database_created"] = 'godkjent - database opprettet';
$_lang["status_checking_database"] = '...    Kontrollerer databasen: ';
$_lang["status_failed_could_not_select_database"] = 'mislyktes - kunne ikke velge database';
$_lang["status_failed_could_not_create_database"] = 'mislyktes - kunne ikke opprette database';
$_lang["status_failed_table_prefix_already_in_use"] = 'mislyktes - tabellprefixet er allerede i bruk!';
$_lang["welcome_message_welcome"] = 'Velkommen til installasjonsprogrammet for MODx.';
$_lang["welcome_message_text"] = 'Dette programmet vil guide deg gjennom hele installasjonen.';
$_lang["welcome_message_select_begin_button"] = 'Klikk på "Start" for å fortsette:';
$_lang["installation_mode"] = 'Installasjonstype';
$_lang["installation_new_installation"] = 'Ny installasjon';
$_lang["installation_install_new_copy"] = 'Installer en ny kopi av ';
$_lang["installation_install_new_note"] = '<br />Vær klar over at dette valget kan skrive over data som finnes i databasen.';
$_lang["installation_upgrade_existing"] = 'Oppgradér eksisterende installasjon';
$_lang["installation_upgrade_existing_note"] = 'Oppgradér dine nåværende filer og database.';
$_lang["installation_upgrade_advanced"] = 'Avansert oppgradering<br />av installasjon<br /><small>(rediger databasens konfigurasjon)</small>';
$_lang["installation_upgrade_advanced_note"] = 'For databasadministratorer eller ved flytting til servere med et annet tegnoppsett for tilkobling.<br /><b>Du vil trenge databasens fulle navn, brukernavn, passord og tilkoblingsdetaljer.</b>';
$_lang["connection_screen_connection_information"] = 'Tilkoblingsopplysninger';
$_lang["connection_screen_server_connection_information"] = 'Serverens tilkoblings- og innloggingsopplysninger';
$_lang["connection_screen_server_connection_note"] = 'Oppgi navnet på din server, ditt innloggningsnavn samt ditt passord og test så tilkoblingen.';
$_lang["connection_screen_server_test_connection"] = 'Klikk her for å teste tilkoblingen til serveren og for å hente tilgjengelige kollasjoneringer';
$_lang["connection_screen_database_connection_information"] = 'Databaseopplysninger';
$_lang["connection_screen_database_connection_note"] = 'Angi navnet på databasen som ble opprettet for MODx. Om det ikke finnes en database fra før, kommer installasjonsprogrammet til å prøve å opprette en for deg. Dette kan mislykkes avhengig av MySQL-konfigurasjonen eller databasens tilgangsrettigheter for ditt domene/installasjon.';
$_lang["connection_screen_database_test_connection"] = 'Klikk her for å opprette din database eller for å teste ditt databasevalg';
$_lang["connection_screen_database_name"] = 'Databasenavn:';
$_lang["connection_screen_table_prefix"] = 'Tabellprefix:';
$_lang["connection_screen_collation"] = 'Kollasjonering:';
$_lang["connection_screen_character_set"] = 'Tegnoppsett for tilkobling:';
$_lang["connection_screen_database_host"] = 'Databasevert:';
$_lang["connection_screen_database_login"] = 'Databasens inloggingsnavn:';
$_lang["connection_screen_database_pass"] = 'Databasens passord:';
$_lang["connection_screen_default_admin_information"] = 'Administrators opplysninger';
$_lang["connection_screen_default_admin_user"] = 'Administratorkonto';
$_lang["connection_screen_default_admin_note"] = 'Nå skal du oppgi noen opplysninger for administratorkontoen. Du må fylle inn ditt eget navn og et passord som du ikke må glemme. Du vil trenge disse opplysningene senere når du skal logge inn på administratorkontoen etter at installasjonen er avsluttet.';
$_lang["connection_screen_default_admin_login"] = 'Administratorens brukernavn:';
$_lang["connection_screen_default_admin_email"] = 'Administratorens epost:';
$_lang["connection_screen_default_admin_password"] = 'Administratorens passord:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Bekreft passord:';
$_lang["optional_items"] = 'Valgbare alternativ';
$_lang["optional_items_note"] = 'Velg dine installasjonsalternativ og klikk Installér:';
$_lang["sample_web_site"] = 'Prøve-data';
$_lang["install_overwrite"] = 'Installér/skriv over';
$_lang["sample_web_site_note"] = 'Vær klar over at dette kommer til å <b style="color:#CC0000;">skrive over</b> eksisterende dokumenter og ressurser.';
$_lang["checkbox_select_options"] = 'Alternativ for kryssbokser:';
$_lang["all"] = 'Alle';
$_lang["none"] = 'Ingen';
$_lang["toggle"] = 'Skift';
$_lang["templates"] = 'Maler';
$_lang["install_update"] = 'Installér/oppdater';
$_lang["chunks"] = 'Chunks';
$_lang["modules"] = 'Moduler';
$_lang["plugins"] = 'Plugins';
$_lang["snippets"] = 'Snippets';
$_lang["preinstall_validation"] = 'Kontroller før installasjon';
$_lang["summary_setup_check"] = 'Installasjonsprogrammet har gjennomført en del tester för å kontrollere at alt er klart for å starte installasjonen.';
$_lang["checking_php_version"] = "Kontrollerer PHP-versjon: ";
$_lang["failed"] = 'mislyktes!';
$_lang["ok"] = 'OK!';
$_lang["you_running_php"] = ' - Du kører PHP ';
$_lang["modx_requires_php"] = ', og MODx krever PHP 4.2.0 eller senere.';
$_lang["php_security_notice"] = '<legend>Sikkerhetsmelding</legend><p>Selv om MODx kommer til å fungere på din PHP-versjon, så anbefales det ikke å bruke MODx med den versjonen. Din PHP-versjon er sårbar for en rekke sikkerhetshull. Oppgrader til PHP-versjon 4.3.8 eller senere, der disse sikkerhetshullene er tettet. Det anbefales at du oppgraderer til denne versjonen for å beskytte ditt nettsted.</p>';
$_lang["checking_registerglobals"] = 'Kontrollerar att Register_Globals är inaktiverad: ';
$_lang["checking_registerglobals_note"] = 'Denne konfigurasjonen gjør din nettside betydelig mer sårbar for webkodeinjeksjon (Cross Site Scripting - XSS). Du bør kontakte din webhost og be om å få inaktivert denne instillingen. Vanligvis lar det seg gjøre på tre følgende måter: modifisering av den globale php.ini-filen, tillegg av regler i en .htaccess-fil i roten på din MODX-installasjon eller gjennom å legge til en tilpasset php.ini-fil (som tilsidesetter den globale filen) i alla kataloger i din installasjon (og det finns masser av dem). Du kan fremdeles installere MODx, men ta denne advarselen på alvor.'; //Look at changing this to provide a solution.
$_lang["checking_sessions"] = 'Kontrollerer at sesjoner er korrekt konfigurert: ';
$_lang["checking_if_cache_exist"] = 'Kontrollerer at katalogen <span class="mono">assets/cache</span> eksisterer: ';
$_lang["checking_if_cache_writable"] = 'Kontrollerer at katalogen <span class="mono">assets/cache</span> er skrivbar: ';
$_lang["checking_if_cache_file_writable"] = 'Kontrollerer at filen <span class="mono">assets/cache/siteCache.idx.php</span> er skrivbar: ';
$_lang["checking_if_cache_file2_writable"] = 'Kontrollerer at filen <span class="mono">assets/cache/sitePublishing.idx.php</span> er skrivbar: ';
$_lang["checking_if_images_exist"] = 'Kontrollerer at katalogen <span class="mono">assets/images</span> eksisterer: ';
$_lang["checking_if_images_writable"] = 'Kontrollerer at katalogen <span class="mono">assets/images</span> er skrivbar: ';
$_lang["checking_if_export_exists"] = 'Kontrollerer at katalogen <span class="mono">assets/export</span> eksisterer: ';
$_lang["checking_if_export_writable"] = 'Kontrollerer at katalogen <span class="mono">assets/export</span> er skrivbar: ';
$_lang["checking_if_config_exist_and_writable"] = 'Kontrollerer at filen <span class="mono">manager/includes/config.inc.php</span> eksisterer og er skrivbar: ';
$_lang["config_permissions_note"] = 'For nye installasjoner i Linux/Unix-miljø må en tom fil med navnet <span class="mono">config.inc.php</span> opprettes i katalogen <span class="mono">manager/includes/</span> med skriverrettighetene satt til 666.';
$_lang["creating_database_connection"] = 'Oppretter en tilkobling til databasen: ';
$_lang["database_connection_failed"] = 'Tilkobling til databasen mislyktes!';
$_lang["database_connection_failed_note"] = 'Kontrollér databasens tilkoblingsopplysninger og forsøk igjen.';
$_lang["database_use_failed"] = 'Databasen kunne ikke velges!';
$_lang["database_use_failed_note"] = 'Kontrollér databasens tilkoblingsrettigheter for den oppgitte brukeren og forsøk igjen.';
$_lang["checking_table_prefix"] = 'Kontrollerer tabellprefixet `';
$_lang["table_prefix_already_inuse"] = ' - Tabellprefixet brukes allerede i denne databasen!';
$_lang["table_prefix_already_inuse_note"] = 'Installasjonsprogrammet kunne ikke installere i den valgte databasen ettersom den allerede inneholder tabeller med det prefixet du oppga. Angi et nytt prefix og kjør installasjonsprogrammet på nytt.';
$_lang["table_prefix_not_exist"] = ' - Tabellprefixet finnes ikke i denne databasen!';
$_lang["table_prefix_not_exist_note"] = 'Installasjonsprogrammet kunne ikke installere i den valgte databasen ettersom den ikke inneholder tabeller med det prefixet du oppga før oppgraderingen. Velg et eksisterende prefix og kjør installasjonsprogrammet på nytt.';
$_lang["setup_cannot_continue"] = 'Installasjonsprogrammet kan desverre ikke fortsette på grunn av ovenforstående ';
$_lang["error"] = 'feil';
$_lang["errors"] = 'feil'; //Plural form
$_lang["please_correct_error"] = '. Korrigér feilen';
$_lang["please_correct_errors"] = '. Korrigér feilen'; //Plural form
$_lang["and_try_again"] = ', og forsøk igjen. Om du trenger hjelp med å finne ut av problemet';
$_lang["and_try_again_plural"] = ', og forsøk igjen. Om du trenger hjelp med å finne ut av problemet'; //Plural form
$_lang["checking_mysql_version"] = 'Sjekker MySQL versjon: ';
$_lang["mysql_version_is"] = ' Din MySQL versjon er: ';
$_lang["mysql_5051_warning"] = 'Det er kjente problemer med MySQL 5.0.51. Det anbefales at du oppgraderer før du fortsetter.';
$_lang["mysql_5051"] = ' MySQL server versjon er 5.0.51!';
$_lang["checking_mysql_strict_mode"] = 'Sjekker MySQL for strict mode: ';
$_lang["strict_mode_error"] = 'MODx krever at strict mode er utkoblet. Du kan sette MySQL tilstanden ved å endre my.cnf filen eller ved å kontakte din serveradministrator.';
$_lang["strict_mode"] = ' MySQL serveren er i strict mode!';
$_lang["visit_forum"] = ', så besøk <a href="http://www.modxcms.com/forums/" target="_blank">MODx forum</a>.';
$_lang["testing_connection"] = 'Kontrollerer tilkoblingen...';
$_lang["btnback_value"] = 'Tilbake';
$_lang["btnnext_value"] = 'Neste';
$_lang["retry"] = 'Forsøk igjen';
$_lang["alert_enter_host"] = 'Du må oppgi et navn på databaseserveren!';
$_lang["alert_enter_login"] = 'Du må oppgi databasens innloggningsnavn!';
$_lang["alert_server_test_connection"] = 'Du må teste servertilkoblingen!';
$_lang["alert_server_test_connection_failed"] = 'Tilkoblingen til serveren mislyktes!';
$_lang["alert_enter_database_name"] = 'Du må oppgi et navn på databasen!';
$_lang["alert_table_prefixes"] = 'Tabellprefixet må begynne med en bokstav!';
$_lang["alert_database_test_connection"] = 'Du må opprette din database eller teste valget av database!';
$_lang["alert_database_test_connection_failed"] = 'Testen på valg av database mislyktes!';
$_lang["alert_enter_adminlogin"] = 'Du må oppgi et brukernavn for systemets administrasjonskonto!';
$_lang["alert_enter_adminpassword"] = 'Du må oppgi et passord for systemets administrasjonskonto!';
$_lang["alert_enter_adminconfirm"] = 'Brukernavnet og passordet til administrasjonskontoen stemmer ikke!';
$_lang["iagree_box"] = 'Jeg aksepterer vilkårene i denne lisensen.';
$_lang["btnclose_value"] = 'Lukk';
$_lang["running_setup_script"] = 'Kjører installasjonsprogrammet... vent litt';
$_lang["modx_footer1"] = '&copy; 2005-2008 the <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Mangement Framework (CMF) project. Med enerett. MODx er lisensiert under GNU GPL.';
$_lang["modx_footer2"] = 'MODx er fri programvare. Vi oppmuntrer deg til å være kreativ og bruke MODx på hvilken måte du vil. Vær bare nøye med å beholde kildekoden fri om du gjør endringer og siden velger å omdistribuere din modifiserte versjon av MODx.';
$_lang["setup_database"] = 'Installasjonsprogrammet kommer nå til å forsøke å konfigurere databasen:<br />';
$_lang["setup_database_create_connection"] = 'Oppretter tilkobling til databasen: ';
$_lang["setup_database_create_connection_failed"] = 'Tilkoblingen til databasen mislyktes!';
$_lang["setup_database_create_connection_failed_note"] = 'Kontroller databasens innloggingsopplysninger og forsøk igjen.';
$_lang["setup_database_selection"] = 'Velger database `';
$_lang["setup_database_selection_failed"] = 'Valg av database mislyktes...';
$_lang["setup_database_selection_failed_note"] = 'Databasen eksisterer ikke. Installasjonsprogrammet kommer til å prøve og opprette den.';
$_lang["setup_database_creation"] = 'Oppretter database `';
$_lang["setup_database_creation_failed"] = 'Databasen kunne ikke opprettes!';
$_lang["setup_database_creation_failed_note"] = ' - Installasjonsprogrammet kunne ikke opprette databasen!';
$_lang["setup_database_creation_failed_note2"] = 'Installasjonsprogrammet kunne ikke opprette databasen og ingen database med samme navn eksisterer. Det er sannsynligvis fordi din webbhosts sikkerhetsinnstillinger ikke tillater at eksterne script oppretter databaser. Opprett en database i følge din webbhosts instruksjoner og kjør installasjonsprogemmet på nytt.';
$_lang["setup_database_creating_tables"] = 'Oppretter databasetabeller: ';
$_lang["database_alerts"] = 'Databasevarsler!';
$_lang["setup_couldnt_install"] = 'MODx installasjonsprogramet kunne ikke legge til/endre noen tabeller i den valgte databasen.';
$_lang["installation_error_occured"] = 'Følgande feil oppsto under installasjonen';
$_lang["during_execution_of_sql"] = ' under kjøringen av SQL-spørringen ';
$_lang["some_tables_not_updated"] = 'Noen tabeller ble ikke oppdatert. Årsaken kan være tidligere modifikasjoner.';
$_lang["installing_demo_site"] = 'Installerer prøve-data: ';
$_lang["writing_config_file"] = 'Skriver konfigurasjonsfil: ';
$_lang["cant_write_config_file"] = 'MODx kunne ikke skrive konfigurasjonsfilen. Kopier følgende til filen ';
$_lang["cant_write_config_file_note"] = 'Når det er klart kan du logge inn i MODx administrasjonskontoen ved å gå til adressen DittDomene.xx/manager/ i din nettleser.';
$_lang["unable_install_template"] = 'Kunne ikke installere template.  Fil';
$_lang["unable_install_chunk"] = 'Kunne ikke installere chunk.  Fil';
$_lang["unable_install_module"] = 'Kunne ikke installere modul.  Fil';
$_lang["unable_install_plugin"] = 'Kunne ikke installere plugin.  Fil';
$_lang["unable_install_snippet"] = 'Kunne ikke installere snippet.  Fil';
$_lang["not_found"] = 'ble ikke funnet';
$_lang["upgraded"] = 'Oppgradert';
$_lang["installed"] = 'Installert';
$_lang["running_database_updates"] = 'Kjør oppdateringer for databasen: ';
$_lang["installation_successful"] = 'Installasjonen er vellykket!';
$_lang["to_log_into_content_manager"] = 'Du kan logge inn i inneholdshåndtereren (manager/index.php)ved å klikke på \"Lukk\"-knappen.';
$_lang["install"] = 'Installér';
$_lang["remove_install_folder_auto"] = 'Ta bort installasjonskatalogen og -filene fra mitt nettsted<br />&nbsp;(Denne operasjonen krever at sletterettigheter er satt for installasjonskatalogen).';
$_lang["remove_install_folder_manual"] = 'Husk å ta bort katalogen &quot;<b>install</b>&quot; før du logger inn i inneholdshåndtereren.';
$_lang["install_results"] = 'Installasjonsresultat';
$_lang["installation_note"] = '<strong>Notér:</strong> Etter å ha logget inn i inneholdshåndtereren bør du redigere og lagre dine systeminstillinger før du begynner å arbeide med ditt nettsted. Gå til Verktøy -> Konfigurasjon i inneholdshåndtereren.';
$_lang["upgrade_note"] = '<strong>Notér:</strong> Før du begynner å bruke ditt nettsted bør du logge inn i inneholdshåndtereren via en administrasjonskonto og kontrollere og lagre dine konfigurasjonsinstillingar.';
?>
