<?php
/**
 * MODx language File
 *
 * @author davaeron
 * @package MODx
 * @version 1.0
 *
 * Filename:       /install/lang/svenska/svenska.inc.php
 * Language:       Svenska
 * Encoding:       utf-8
 *
 * Translation: Pontus Ågren (Pont) & Thomas Djärv (Beryl)
 * Date: 2008-05-22
 */




$_lang['license'] = '<p class="title">MODx Licensavtal</p>
    <hr style="text-align:left;height:1px;width:90%;" />
    <h4>Du måste godkänna licensen innan installationen kan fortsätta.</h4>
    <p>Användning av den här mjukvaran lyder under GPL-licensen. För att hjälpa dig förstå
       vad GPL-licensen är och hur den påverkar dina möjligheter att använda mjukvaran, så
       har vi gjort följande sammanfattning.</p>
    <h4>GNU General Public License (GPL) är en licens för Fri Mjukvara.</h4>
    <p>Som alla licenser för Fri Mjukvara ger den dig följande fyra friheter:</p>
    <ul>
        <li>Friheten att köra programmet för alla ändamål.</li>
        <li>Friheten att studera hur programmet fungerar och anpassa det efter dina önskemål.</li>
        <li>Friheten att distribuera kopior så att du kan hjälpa din granne. </li>
        <li>Friheten att förbättra programmet och publicera dina förbättringar till
            allmänheten, så att hela gemenskapen kan dra nytta av dom.</li>
    </ul>
    <p>Du kan åtnjuta de ovan specificerade friheterna förutsatt att du följer
       de uttryckliga krav som licensen uttalar. De huvudsakliga kraven är:</p>
    <ul>
        <li>Du måste på varje kopia du distribuerar, tydligt och på lämpligt sätt
            publicera ett tillämpligt meddelande om copyright och en garantifriskrivning,
            behålla alla meddelanden som refererar till den här licensen och frånvaron
            av garanti samt ge varje mottagare av programmet en kopia av GNU GPL-licensen
            tillsammans med programmet. Alla översättningar av GNU GPL-licensen måste
            åtföljas av det oöversatta originalet.</li>

        <li>Om du modifierar din kopia eller kopior av programmet eller någon del av det,
            eller utvecklar ett program baserat på det, så får du distribuera det resulterande
            arbetet förutsatt att du gör det under GNU GPL-licensen. Alla översättningar av
            GNU GPL-licensen måste åtföljas av det oöversatta originalet.</li>

        <li>Om du kopierar eller distribuerar programmet, så måste det åtföljas av den
            motsvarande kompletta maskinläsbara källkoden eller ett skriftligt erbjudande,
            giltigt minst tre år, att lämna ut den motsvarande kompletta maskinläsbara
            källkoden.</li>

        <li>Ovanstående krav kan hävas om du ges tillåtelse av innehavaren av copyrighten.</li>

        <li>Din rätt att citera samt andra rättigheter påverkas inte av ovanstående.</li>
    </ul>
    <p>Ovanstående är en sammanfattning av GNU GPL-licensen. Genom att fortsätta godkänner du
       GNU GPL-licensen - inte ovanstående. Ovanstående är en enkel sammanfattning och dess korrekthet
       kan inte garanteras. En stark rekommendation är att du läser den fullständiga <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL-licensen</a> innan du
       fortsätter. Den återfinns också i filen license.txt som distribueras tillsammans med det här
       programmet.<br />
       Det finns en <a href="http://danielnylander.se/gpl/" target="_blank">inofficiell svensk
       översättning</a>.</p>';
$_lang["encoding"] = 'utf-8';	//charset encoding for html header
$_lang["modx_install"] = 'MODx &raquo; Installation';
$_lang["loading"] = 'Laddar...';
$_lang["Begin"] = 'Starta';
$_lang["status_connecting"] = ' Anslutning till värddatorn: ';
$_lang["status_failed"] = 'misslyckades!';
$_lang["status_passed"] = 'godkänd - databasen valdes';
$_lang["status_passed_server"] = 'godkänd - kollationeringar finns nu tillgängliga';
$_lang["status_passed_database_created"] = 'godkänd - databas skapades';
$_lang["status_checking_database"] = 'Kontrollerar databas: ';
$_lang["status_failed_could_not_select_database"] = 'misslyckades - kunde inte välja databas';
$_lang["status_failed_could_not_create_database"] = 'misslyckades - kunde inte skapa databas';
$_lang["status_failed_table_prefix_already_in_use"] = 'misslyckades - tabellprefixet används redan!';
$_lang["welcome_message_welcome"] = 'Välkommen till MODx installationsprogram.';
$_lang["welcome_message_text"] = 'Detta program kommer att vägleda dig genom hela installationen.';
$_lang["welcome_message_select_begin_button"] = 'Klicka på "Starta" för att fortsätta:';
$_lang["installation_mode"] = 'Installationstyp';
$_lang["installation_new_installation"] = 'Ny installation';
$_lang["installation_install_new_copy"] = 'Installera en ny kopia av ';
$_lang["installation_install_new_note"] = '<br />Observera att detta valet kan skriva över data som finns i databasen.';
$_lang["installation_upgrade_existing"] = 'Uppgradera existerande<br />installation';
$_lang["installation_upgrade_existing_note"] = 'Uppgradera dina nuvarande filer och databas.';
$_lang["installation_upgrade_advanced"] = 'Avancerad uppgradering<br />av installation<br /><small>(redigera databasens konfiguration)</small>';
$_lang["installation_upgrade_advanced_note"] = 'För avancerade databasadministratörer eller vid flytt till servrar med en annan teckenuppsättning för anslutning.<br /><b>Du kommer att behöva databasens fullständiga namn, användarenamn, lösenord och anslutnings/kollationeringsdetaljer.</b>';
$_lang["connection_screen_connection_information"] = 'Anslutningsuppgifter';
$_lang["connection_screen_server_connection_information"] = 'Serverns anslutnings- och inloggningsuppgifter';
$_lang["connection_screen_server_connection_note"] = 'Ange namnet på din server, ditt inloggningsnamn samt ditt lösenord och testa sedan anslutningen.';
$_lang["connection_screen_server_test_connection"] = 'Klicka här för att testa anslutningen till servern och för att hämta tillgängliga kollationeringar';
$_lang["connection_screen_database_connection_information"] = 'Databasuppgifter';
$_lang["connection_screen_database_connection_note"] = 'Ange namnet på databasen som skapats för MODx. Om det inte finns någon databas än kommer installationsprogrammet att försöka skapa en åt dig. Det här kan komma att misslyckas beroende på MySQL-konfigurationen eller databasens åtkomsträttigheter för din domän/installation.';
$_lang["connection_screen_database_test_connection"] = 'Klicka här för att skapa din databas eller för att testa ditt databasval';
$_lang["connection_screen_database_name"] = 'Databasnamn:';
$_lang["connection_screen_table_prefix"] = 'Tabellprefix:';
$_lang["connection_screen_collation"] = 'Kollationering:';
$_lang["connection_screen_character_set"] = 'Teckenuppsättning för anslutning:';
$_lang["connection_screen_database_host"] = 'Databasens värd:';
$_lang["connection_screen_database_login"] = 'Databasens inloggningsnamn:';
$_lang["connection_screen_database_pass"] = 'Databasens lösenord:';
$_lang["connection_screen_default_admin_information"] = 'Administratörsuppgifter';
$_lang["connection_screen_default_admin_user"] = 'Administratörskonto';
$_lang["connection_screen_default_admin_note"] = 'Nu ska du ange ett antal uppgifter för det administrativa kontot. Du kan fylla i ditt eget namn här och ett lösenord som du inte glömmer i första taget. Du kommer att behöva de här uppgifterna när du ska logga in på det administrativa kontot efter att installationen är klar.';
$_lang["connection_screen_default_admin_login"] = 'Administratörens användarnamn:';
$_lang["connection_screen_default_admin_email"] = 'Administratörens epost:';
$_lang["connection_screen_default_admin_password"] = 'Administratörens lösenord:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Bekräfta lösenord:';
$_lang["optional_items"] = 'Valbara alternativ';
$_lang["optional_items_note"] = 'Ange dina installationsalternativ och klicka på Installera:';
$_lang["sample_web_site"] = 'Prov-webbplats';
$_lang["install_overwrite"] = 'Installera/skriv över';
$_lang["sample_web_site_note"] = 'Observera att detta kommer att <b style="color:#CC0000;">skriva över</b> existerande dokument och resurser.';
$_lang["checkbox_select_options"] = 'Alternativ för kryssrutor:';
$_lang["all"] = 'Alla';
$_lang["none"] = 'Inga';
$_lang["toggle"] = 'Växla';
$_lang["templates"] = 'Mallar';
$_lang["install_update"] = 'Installera/uppdatera';
$_lang["chunks"] = 'Chunks';
$_lang["modules"] = 'Moduler';
$_lang["plugins"] = 'Plugins';
$_lang["snippets"] = 'Snippets';
$_lang["preinstall_validation"] = 'Kontroller innan installation';
$_lang["summary_setup_check"] = 'Installationsprogrammet har gjort ett antal test för att kontrollera att allt är klart för att starta installationen.';
$_lang["checking_php_version"] = "Kontrollerar PHP-version: ";
$_lang["failed"] = 'Misslyckades!';
$_lang["ok"] = 'OK!';
$_lang["you_running_php"] = ' - Du kör PHP ';
$_lang["modx_requires_php"] = ', och MODx kräver PHP 4.1.0 eller senare.';
$_lang["php_security_notice"] = '<legend>Säkerhetsmeddelande</legend><p>Även om MODx kommer att fungera med din PHP-version, så rekommenderas det inte att använda MODx med den versionen. Din PHP-version är sårbar för ett antal säkerhetshål. Uppgradera till PHP-version 4.3.8 eller senare, vilka åtgärdar de här hålen. Det rekommenderas att du uppgraderar till den här versionen för att få en bättre säkerhet på din webbplats.</p>';
$_lang["checking_registerglobals"] = 'Kontrollerar att Register_Globals är inaktiverad: ';
$_lang["checking_registerglobals_note"] = 'Denna konfiguration gör din webbplats betydligt mer sårbar för webbkodsinjektion (Cross Site Scripting - XSS). Du bör kontakta din webbhost om att inaktivera denna inställning. Vanligen går det till på ett av följande tre sätt: modifiering av den globala php.ini-filen, tillägg av regler i en .htaccess-fil i roten på din MODX-installation eller genom att lägga till en anpassad php.ini-fil (som åsidosätter den globala filen) i alla kataloger i din installation (och det finns massor av dom). Du kan fortfarande installera MODx, men ta den här varningen på allvar.'; //Look at changing this to provide a solution.
$_lang["checking_sessions"] = 'Kontrollerar att sessioner är korrekt konfigurerade: ';
$_lang["checking_if_cache_exist"] = 'Kontrollerar att katalogen <span class="mono">assets/cache</span> existerar: ';
$_lang["checking_if_cache_writable"] = 'Kontrollerar att katalogen <span class="mono">assets/cache</span> är skrivbar: ';
$_lang["checking_if_cache_file_writable"] = 'Kontrollerar att filen <span class="mono">assets/cache/siteCache.idx.php</span> är skrivbar: ';
$_lang["checking_if_cache_file2_writable"] = 'Kontrollerar att filen <span class="mono">assets/cache/sitePublishing.idx.php</span> är skrivbar: ';
$_lang["checking_if_images_exist"] = 'Kontrollerar att katalogen <span class="mono">assets/images</span> existerar: ';
$_lang["checking_if_images_writable"] = 'Kontrollerar att katalogen <span class="mono">assets/images</span> är skrivbar: ';
$_lang["checking_if_export_exists"] = 'Kontrollerar att katalogen <span class="mono">assets/export</span> existerar: ';
$_lang["checking_if_export_writable"] = 'Kontrollerar att katalogen <span class="mono">assets/export</span> är skrivbar: ';
$_lang["checking_if_config_exist_and_writable"] = 'Kontrollerar att filen <span class="mono">manager/includes/config.inc.php</span> existerar och är skrivbar: ';
$_lang["config_permissions_note"] = 'För nya installationer i Linux/Unix-miljö måste en tom fil med namnet <span class="mono">config.inc.php</span> skapas i katalogen <span class="mono">manager/includes/</span> med åtkomsträttigheterna satta till 0666.';
$_lang["creating_database_connection"] = 'Skapar en anslutning till databasen: ';
$_lang["database_connection_failed"] = 'Anslutningen till databasen misslyckades!';
$_lang["database_connection_failed_note"] = 'Kontrollera databasens inloggningsuppgifter och försök igen.';
$_lang["database_use_failed"] = 'Databasen kunde inte väljas!';
$_lang["database_use_failed_note"] = 'Kontrollera databasens åtkomsträttigheter för den angivna användaren och försök igen.';
$_lang["checking_table_prefix"] = 'Kontrollerar tabellprefixet `';
$_lang["table_prefix_already_inuse"] = ' - Tabellprefixet används redan i den här databasen!';
$_lang["table_prefix_already_inuse_note"] = 'Installationsprogrammet kunde inte installera i den valda databasen eftersom den redan innehåller tabeller med det prefix du angav. Ange ett nytt prefix och kör installationsprogrammet igen.';
$_lang["table_prefix_not_exist"] = ' - Tabellprefixet finns inte i den här databasen!';
$_lang["table_prefix_not_exist_note"] = 'Installationsprogrammet kunde inte installera i den valda databasen eftersom den inte innehåller tabeller med det prefix du angav för uppgradering. Välj ett existerande prefix och kör installationsprogrammet igen.';
$_lang["setup_cannot_continue"] = 'Installationsprogrammet kan tyvärr inte fortsätta på grund av ovanstående ';
$_lang["error"] = 'fel';
$_lang["errors"] = 'fel'; //Plural form
$_lang["please_correct_error"] = '. Korrigera felet';
$_lang["please_correct_errors"] = '. Korrigera felen'; //Plural form
$_lang["and_try_again"] = ', och försök igen. Om du behöver hjälp med att klura ut hur du ska åtgärda problemet';
$_lang["and_try_again_plural"] = ', och försök igen. Om du behöver hjälp med att klura ut hur du ska åtgärda problemen'; //Plural form
$_lang["checking_mysql_version"] = 'Kontrollerar MySQL-versionen: ';
$_lang["mysql_version_is"] = ' Din MySQL-version är: ';
$_lang["mysql_5051_warning"] = 'Det finns kända problem med MySQL 5.0.51. Du rekommenderas att uppgradera innan du fortsätter.';
$_lang["mysql_5051"] = ' MySQL-serverns version är 5.0.51!';
$_lang["checking_mysql_strict_mode"] = 'Kontrollerar om MySQL är i strikt läge (strict mode): ';
$_lang["strict_mode_error"] = 'MODx kräver att strikt läge är inaktiverat. Du kan ställa in MySQLs läge genom att redigera filen my.cnf eller genom att kontakta din serveradministratör.';
$_lang["strict_mode"] = ' MySQL-servern är i strikt läge!';
$_lang["visit_forum"] = ', så besök <a href="http://www.modxcms.com/forums/" target="_blank">MODx forum</a>.';
$_lang["testing_connection"] = 'Kontrollerar anslutningen...';
$_lang["btnback_value"] = 'Tillbaka';
$_lang["btnnext_value"] = 'Nästa';
$_lang["retry"] = 'Försök igen';
$_lang["alert_server_test_connection"] = 'Du behöver testa din anslutning till servern!';
$_lang["alert_server_test_connection_failed"] = 'Testen av din databasanslutning har misslyckats!';
$_lang["alert_enter_database_name"] = 'Du måste ange ett namn på databasen!';
$_lang["alert_table_prefixes"] = 'Tabellprefix måste börja med en bokstav!';
$_lang["alert_database_test_connection"] = 'Du behöver skapa din databas eller testa det databasval du gjort!';
$_lang["alert_database_test_connection_failed"] = 'Testet av ditt databasval har misslyckats!';
$_lang["alert_enter_host"] = 'Du måste ange en värd för databasen!';
$_lang["alert_enter_login"] = 'Du måste ange databasens inloggningsnamn!';
$_lang["alert_enter_adminlogin"] = 'Du måste ange ett användarnamn för systemets administrativa konto!';
$_lang["alert_enter_adminpassword"] = 'Du måste ange ett lösenord för systemets administrativa konto!';
$_lang["alert_enter_adminconfirm"] = 'Det administrativa lösenordet och bekräftelsen överensstämmer inte!';
$_lang["iagree_box"] = 'Jag godkänner villkoren i denna licens.';
$_lang["btnclose_value"] = 'Stäng';
$_lang["running_setup_script"] = 'Kör installationsprogrammet... vänta lite';
$_lang["modx_footer1"] = '&copy; 2005-2008 the <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Mangement Framework (CMF) project. Med ensamrätt. MODx är licensierad under GNU GPL.';
$_lang["modx_footer2"] = 'MODx är fri programvara. Vi uppmuntrar dig att vara kreativ och använda MODx på vilket sätt du vill. Vara bara noga med att bevara källkoden fri om du gör ändringar och sedan väljer att omdistribuera din modifierade version av MODx.';
$_lang["setup_database"] = 'Installationsprogrammet kommer nu att försöka konfigurera databasen:<br />';
$_lang["setup_database_create_connection"] = 'Skapar anslutning till databasen: ';
$_lang["setup_database_create_connection_failed"] = 'Anslutningen till databasen misslyckades!';
$_lang["setup_database_create_connection_failed_note"] = 'Kontrollera databasens inloggningsuppgifter och försök igen.';
$_lang["setup_database_selection"] = 'Väljer databas `';
$_lang["setup_database_selection_failed"] = 'Val av databas misslyckades...';
$_lang["setup_database_selection_failed_note"] = 'Databasen existerar inte. Installationsprogrammet kommer att försöka skapa den.';
$_lang["setup_database_creation"] = 'Skapar databas `';
$_lang["setup_database_creation_failed"] = 'Databasen kunde inte skapas!';
$_lang["setup_database_creation_failed_note"] = ' - Installationsprogrammet kunde inte skapa databasen!';
$_lang["setup_database_creation_failed_note2"] = 'Installationsprogrammet kunde inte skapa databasen och ingen databas med samma namn existerar. Det är troligt att din webbhosts säkerhetsintällningar inte tillåter externa script att skapa en databas. Skapa en databas enligt webbhostens instruktioner och kör installationsprogrammet igen.';
$_lang["setup_database_creating_tables"] = 'Skapar databastabeller: ';
$_lang["database_alerts"] = 'Databasvarningar!';
$_lang["setup_couldnt_install"] = 'MODx installationsprogram kunde inte lägga till/ändra några tabeller i den valda databasen.';
$_lang["installation_error_occured"] = 'Följande fel uppstog under installationen';
$_lang["during_execution_of_sql"] = ' under körningen av SQL-frågan ';
$_lang["some_tables_not_updated"] = 'Några tabeller uppdaterades inte. Det här kan bero på tidigare modifikationer.';
$_lang["installing_demo_site"] = 'Installerar prov-webbplats: ';
$_lang["writing_config_file"] = 'Skriver konfigurationsfil: ';
$_lang["cant_write_config_file"] = 'MODx kunde inte skriva konfigurationsfilen. Kopiera följande till filen ';
$_lang["cant_write_config_file_note"] = 'När det är klart kan du logga in i MODx administrationskonto genom att ange adressen DinWebbplats.se/manager/ i din webbläsare.';
$_lang["unable_install_template"] = 'Kunde inte installera mall.  Fil';
$_lang["unable_install_chunk"] = 'Kunde inte installera chunk.  Fil';
$_lang["unable_install_module"] = 'Kunde inte installera modul.  Fil';
$_lang["unable_install_plugin"] = 'Kunde inte installera plugin.  Fil';
$_lang["unable_install_snippet"] = 'Kunde inte installera snippet.  Fil';
$_lang["not_found"] = 'hittades inte';
$_lang["upgraded"] = 'Uppgraderad';
$_lang["installed"] = 'Installerad';
$_lang["running_database_updates"] = 'Kör uppdateringar för databasen: ';
$_lang["installation_successful"] = 'Installationen lyckades!';
$_lang["to_log_into_content_manager"] = 'Du kan logga in i innehållshanteraren (manager/index.php) genom att klicka på "Stäng"-knappen.';
$_lang["install"] = 'Installera';
$_lang["remove_install_folder_auto"] = 'Ta bort installationskatalogen och -filerna från min webbplats.<br />&nbsp;(Den här operationen kräver att raderingsrättigheter är angivna för installationskatalogen)';
$_lang["remove_install_folder_manual"] = 'Kom ihåg att ta bort katalogen &quot;<b>install</b>&quot; innan du loggar in i innehållshanteraren.';
$_lang["install_results"] = 'Installationsresultat';
$_lang["installation_note"] = '<strong>Notera:</strong> Efter att ha loggat in i innehållshanteraren bör du redigera och spara dina systeminställningar innan du börjar surfa på din webbplats. Gå till Verktyg -> Konfiguration i innehållshanteraren.';
$_lang["upgrade_note"] = '<strong>Notera:</strong> Innan du börjar surfa på din webbplats bör du logga in i innehållshanteraren på ett administrativt konto och kontrollera och spara dina konfigurationsinställningar.';
?>