<?php
/**
 * MODX language File
 *
 * @author davaeron
 * @package MODX
 * @version 1.0
 *
 * Filename:       /install/lang/svenska/svenska.inc.php
 * Language:       Svenska
 * Encoding:       utf-8
 * Revision:       6624
 *
 * Translation: Pontus Ågren (Pont) & Thomas Djärv (Beryl)
 * Date: 2010-03-29
 */

$_lang["agree_to_terms"] = 'Godkänn licensvillkoren och installera';
$_lang["alert_database_test_connection"] = 'Du behöver skapa din databas eller testa det databasval du gjort!';
$_lang["alert_database_test_connection_failed"] = 'Testet av ditt databasval har misslyckats!';
$_lang["alert_enter_adminconfirm"] = 'Det administrativa lösenordet och bekräftelsen överensstämmer inte!';
$_lang["alert_enter_adminlogin"] = 'Du måste ange ett användarnamn för systemets administrativa konto!';
$_lang["alert_enter_adminpassword"] = 'Du måste ange ett lösenord för systemets administrativa konto!';
$_lang["alert_enter_database_name"] = 'Du måste ange ett namn på databasen!';
$_lang["alert_enter_host"] = 'Du måste ange en värd för databasen!';
$_lang["alert_enter_login"] = 'Du måste ange databasens inloggningsnamn!';
$_lang["alert_server_test_connection"] = 'Du behöver testa din anslutning till servern!';
$_lang["alert_server_test_connection_failed"] = 'Testet av din databasanslutning misslyckades!';
$_lang["alert_table_prefixes"] = 'Tabellprefix måste börja med en bokstav!';
$_lang["all"] = 'Alla';
$_lang["and_try_again"] = ', och försök igen. Om du behöver hjälp med att klura ut hur du ska åtgärda problemet';
$_lang["and_try_again_plural"] = ', och försök igen. Om du behöver hjälp med att klura ut hur du ska åtgärda problemen';
$_lang["begin"] = 'Starta';
$_lang["btnback_value"] = 'Tillbaka';
$_lang["btnclose_value"] = 'Stäng';
$_lang["btnnext_value"] = 'Nästa';
$_lang["cant_write_config_file"] = 'MODX kunde inte skriva konfigurationsfilen. Kopiera följande till filen ';
$_lang["cant_write_config_file_note"] = 'När det är klart kan du logga in i MODX administrationsdel genom att ange adressen DinWebbplats.se/[+MGR_DIR+]/ i din webbläsare.';
$_lang["checkbox_select_options"] = 'Välj flera element:';
$_lang["checking_if_cache_exist"] = 'Kontrollerar att katalogerna <span class="mono">/assets/cache</span> och <span class="mono">/assets/cache/rss</span> existerar: ';
$_lang["checking_if_cache_file_writable"] = 'Kontrollerar att filen <span class="mono">/assets/cache/siteCache.idx.php</span> är skrivbar: ';
$_lang["checking_if_cache_file2_writable"] = 'Kontrollerar att filen <span class="mono">/assets/cache/sitePublishing.idx.php</span> är skrivbar: ';
$_lang["checking_if_cache_writable"] = 'Kontrollerar att katalogerna <span class="mono">/assets/cache</span> och <span class="mono">/assets/cache/rss</span> är skrivbara: ';
$_lang["checking_if_config_exist_and_writable"] = 'Kontrollerar att filen <span class="mono">/[+MGR_DIR+]/includes/config.inc.php</span> existerar och är skrivbar: ';
$_lang["checking_if_export_exists"] = 'Kontrollerar att katalogen <span class="mono">/assets/export</span> existerar: ';
$_lang["checking_if_export_writable"] = 'Kontrollerar att katalogen <span class="mono">/assets/export</span> är skrivbar: ';
$_lang["checking_if_images_exist"] = 'Kontrollerar att katalogerna <span class="mono">/assets/images</span>, <span class="mono">/assets/files</span>, <span class="mono">/assets/flash</span>, <span class="mono">/assets/media</span>, <span class="mono">/assets/backup</span> och <span class="mono">/assets/.thumbs</span> existerar: ';
$_lang["checking_if_images_writable"] = 'Kontrollerar att katalogerna <span class="mono">/assets/images</span>, <span class="mono">/assets/files</span>, <span class="mono">/assets/flash</span>, <span class="mono">/assets/media</span>, <span class="mono">/assets/backup</span> och <span class="mono">/assets/.thumbs</span> är skrivbara: ';
$_lang["checking_mysql_strict_mode"] = 'Kontrollerar MySQL för strikt sql_mode: ';
$_lang["checking_mysql_version"] = 'Kontrollerar MySQL-versionen: ';
$_lang["checking_php_version"] = 'Kontrollerar PHP-version: ';
$_lang["checking_registerglobals"] = 'Kontrollerar att Register_Globals är inaktiverad: ';
$_lang["checking_registerglobals_note"] = 'Denna konfiguration gör din webbplats betydligt mer sårbar för attacker med webbkodsinjektion (Cross Site Scripting - XSS). Du bör kontakta din webbhost om att inaktivera denna inställning. Vanligen går det till på ett av följande tre sätt: modifiering av den globala php.ini-filen, tillägg av regler i en .htaccess-fil i roten på din MODX-installation eller genom att lägga till en anpassad php.ini-fil (som åsidosätter den globala filen) i alla kataloger i din installation (och det finns massor av dom). Du kan fortfarande installera MODX, men ta den här varningen på allvar.'; //Look at changing this to provide a solution.
$_lang["checking_sessions"] = 'Kontrollerar att sessioner är korrekt konfigurerade: ';
$_lang["checking_table_prefix"] = 'Kontrollerar tabellprefixet `';
$_lang["chunks"] = 'Chunks';
$_lang["config_permissions_note"] = 'För nya installationer i Linux/Unix-miljö måste en tom fil med namnet <span class="mono">config.inc.php</span> skapas i katalogen <span class="mono">/[+MGR_DIR+]/includes/</span> med åtkomsträttigheterna satta till 0666.';
$_lang["connection_screen_collation"] = 'Kollationering:';
$_lang["connection_screen_connection_method"] = 'Anslutningsmetod:';
$_lang["connection_screen_database_connection_information"] = 'Databasuppgifter';
$_lang["connection_screen_database_connection_note"] = 'Ange namnet på den databas som ska användas eller som du vill skapa för denna MODX-installation. Om det inte finns någon databas kommer installationsprogrammet att försöka skapa en. Det här kan misslyckas beroende på hur MySQLs åtkomsträttigheter är konfigurerade.';
$_lang["connection_screen_database_host"] = 'Databasens värd:';
$_lang["connection_screen_database_info"] = 'Databasinformation';
$_lang["connection_screen_database_login"] = 'Databasens inloggningsnamn:';
$_lang["connection_screen_database_name"] = 'Databasnamn:';
$_lang["connection_screen_database_pass"] = 'Databasens lösenord:';
$_lang["connection_screen_database_test_connection"] = 'Skapa databas eller testa ditt databasval';
$_lang["connection_screen_default_admin_email"] = 'Administratörens e-post:';
$_lang["connection_screen_default_admin_login"] = 'Administratörens användarnamn:';
$_lang["connection_screen_default_admin_note"] = 'Nu ska du ange ett antal uppgifter för det administrativa kontot. Du kan fylla i ditt eget namn här och ett lösenord som du inte glömmer i första taget. Du kommer att behöva de här uppgifterna när du ska logga in på det administrativa kontot efter att installationen är klar.';
$_lang["connection_screen_default_admin_password"] = 'Administratörens lösenord:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Bekräfta lösenord:';
$_lang["connection_screen_default_admin_user"] = 'Administratörskonto';
$_lang["connection_screen_defaults"] = 'Standardinställningar för hanteraren';
$_lang["connection_screen_server_connection_information"] = 'Serverns anslutnings- och inloggningsuppgifter';
$_lang["connection_screen_server_connection_note"] = 'Ange databasnamnet (servernamnet eller IP-adressen), användarnamnet och lösenordet innan du testar anslutningen.';
$_lang["connection_screen_server_test_connection"] = 'Testa anslutningen till databasservern och visa tillgängliga kollationeringar';
$_lang["connection_screen_table_prefix"] = 'Tabellprefix:';
$_lang["creating_database_connection"] = 'Skapar en anslutning till databasen: ';
$_lang["database_alerts"] = 'Databasvarningar!';
$_lang["database_connection_failed"] = 'Anslutningen till databasen misslyckades!';
$_lang["database_connection_failed_note"] = 'Kontrollera databasens inloggningsuppgifter och försök igen.';
$_lang["database_use_failed"] = 'Databasen kunde inte väljas!';
$_lang["database_use_failed_note"] = 'Kontrollera databasens åtkomsträttigheter för den angivna användaren och försök igen.';
$_lang["default_language"] = 'Standardspråk i hanteraren';
$_lang["default_language_description"] = 'Det här är det standardspråk som kommer att användas i MODX hanterare.';
$_lang["during_execution_of_sql"] = ' under körningen av SQL-frågan ';
$_lang["encoding"] = 'utf-8';   //charset encoding for html header
$_lang["error"] = 'fel';
$_lang["errors"] = 'fel';
$_lang["failed"] = 'MISSLYCKADES!';
$_lang["help"] = 'Hjälp!';
$_lang["help_link"] = 'http://forums.modx.com/';
$_lang["help_title"] = 'Installationshjälp i MODX forum';
$_lang["iagree_box"] = 'Jag godkänner <a href="../assets/docs/license.txt" target="_blank">licensvillkoren för MODX</a>.<br />Om du vill läsa en översättning av GPL-licensen, version 2, hittar du den på <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0-translations.html" target="_blank">operativsystemet GNUs webbplats</a>.';
$_lang["install"] = 'Installera';
$_lang["install_overwrite"] = 'Installera/skriv över';
$_lang["install_results"] = 'Installationsresultat';
$_lang["install_update"] = 'Installera/uppdatera';
$_lang["installation_error_occured"] = 'Följande fel inträffade under installationen';
$_lang["installation_install_new_copy"] = 'Installera en ny kopia av ';
$_lang["installation_install_new_note"] = 'Observera att detta val kan skriva över data som finns i databasen.';
$_lang["installation_mode"] = 'Installationstyp';
$_lang["installation_new_installation"] = 'Ny installation';
$_lang["installation_note"] = '<strong>Notera:</strong> Efter att ha loggat in i innehållshanteraren bör du redigera och spara dina systeminställningar innan du börjar surfa på din webbplats. Gå till <strong>Verktyg</strong> -> Konfiguration i innehållshanteraren.';
$_lang["installation_successful"] = 'Installationen lyckades!';
$_lang["installation_upgrade_advanced"] = 'Avancerad uppgradering';
$_lang["installation_upgrade_advanced_note"] = 'För avancerade databasadministratörer eller vid flytt till servrar med en annan teckenuppsättning för anslutning.<br /><b>Du kommer att behöva databasens fullständiga namn, användarnamn, lösenord och anslutnings/kollationeringsuppgifter.</b>';
$_lang["installation_upgrade_existing"] = 'Uppgradera befintlig installation';
$_lang["installation_upgrade_existing_note"] = 'Uppgradera dina nuvarande filer och databas.';
$_lang["installed"] = 'Installerad';
$_lang["installing_demo_site"] = 'Installerar demo-webbplats: ';
$_lang["language_code"] = 'sv'; // for html element e.g. <html xml:lang="sv" lang="sv">
$_lang["loading"] = 'Laddar...';
$_lang["modules"] = 'Moduler';
$_lang["modx_footer1"] = '&copy; 2005-2013 the <a href="http://modx.com/" target="_blank" style="color: green; text-decoration:underline">MODX</a> Content Mangement Framework (CMF) project. Med ensamrätt. MODX är licensierad under GNU GPL.';
$_lang["modx_footer2"] = 'MODX är fri programvara. Vi uppmuntrar dig att vara kreativ och använda MODX på vilket sätt du vill. Vara bara noga med att bevara källkoden fri om du gör ändringar och sedan väljer att omdistribuera din modifierade version av MODX.';
$_lang["modx_install"] = 'MODX &raquo; Installation';
$_lang["modx_requires_php"] = ', och MODX kräver PHP 4.2.0 eller senare.';
$_lang["mysql_5051"] = ' MySQL-serverns version är 5.0.51!';
$_lang["mysql_5051_warning"] = 'Det finns kända problem med MySQL 5.0.51. Du rekommenderas att uppgradera innan du fortsätter.';
$_lang["mysql_version_is"] = ' Din MySQL-version är: ';
$_lang["no"] = 'Nej';
$_lang["none"] = 'Inga';
$_lang["not_found"] = 'hittades inte';
$_lang["ok"] = 'OK!';
$_lang["optional_items"] = 'Valbara alternativ';
$_lang["optional_items_note"] = 'Välj dina installationsalternativ och klicka på Installera:';
$_lang["php_security_notice"] = '<legend>Säkerhetsmeddelande</legend><p>Även om MODX kommer att fungera med din PHP-version, så rekommenderas det inte att använda MODX med den versionen. Din PHP-version är sårbar för ett antal säkerhetshål. Uppgradera till PHP-version 4.3.8 eller senare, vilket åtgärdar de här hålen. Det rekommenderas att du uppgraderar till den här versionen för att få en bättre säkerhet på din webbplats.</p>';
$_lang["please_correct_error"] = '. Korrigera felet';
$_lang["please_correct_errors"] = '. Korrigera felen';
$_lang["plugins"] = 'Plugins';
$_lang["preinstall_validation"] = 'Kontroller innan installation';
$_lang["recommend_setting_change_title"] = 'Rekommenderad inställningsändring';
$_lang["recommend_setting_change_validate_referer_confirmation"] = 'Inställningsändring: <em>Validera HTTP_REFERER-headers?</em>';
$_lang["recommend_setting_change_validate_referer_description"] = 'Din webbplats är inte konfigurerad för att validera HTTP_REFERER på inkommande förfrågningar till hanteraren. Vi rekommenderar starkt att du aktiverar den inställningen för att minska risken för CSRF-attacker (Cross Site Request Forgery).';
$_lang["remove_install_folder_auto"] = 'Ta bort installationskatalogen och -filerna från min webbplats.<br />&nbsp;(Den här operationen kräver att raderingsrättigheter är angivna för installationskatalogen)';
$_lang["remove_install_folder_manual"] = 'Kom ihåg att ta bort katalogen &quot;<b>install</b>&quot; innan du loggar in i innehållshanteraren.';
$_lang["retry"] = 'Försök igen';
$_lang["running_database_updates"] = 'Kör uppdateringar för databasen: ';
$_lang["sample_web_site"] = 'Demo-webbplats';
$_lang["sample_web_site_note"] = 'Observera att detta kommer att <b>skriva över</b> existerande dokument och resurser.';
$_lang["session_problem"] = 'Ett problem med dina serversessioner upptäcktes. Kontakta din serveradministratör för att rätta till problemet.';
$_lang["session_problem_try_again"] = 'Försök igen?'; 
$_lang["setup_cannot_continue"] = 'Installationsprogrammet kan tyvärr inte fortsätta på grund av ovanstående ';
$_lang["setup_couldnt_install"] = 'MODX installationsprogram kunde inte lägga till/ändra några tabeller i den valda databasen.';
$_lang["setup_database"] = 'Installationsprogrammet kommer nu att försöka konfigurera databasen:<br />';
$_lang["setup_database_create_connection"] = 'Skapar anslutning till databasen: ';
$_lang["setup_database_create_connection_failed"] = 'Anslutningen till databasen misslyckades!';
$_lang["setup_database_create_connection_failed_note"] = 'Kontrollera databasens inloggningsuppgifter och försök igen.';
$_lang["setup_database_creating_tables"] = 'Skapar databastabeller: ';
$_lang["setup_database_creation"] = 'Skapar databas `';
$_lang["setup_database_creation_failed"] = 'Databasen kunde inte skapas!';
$_lang["setup_database_creation_failed_note"] = ' - Installationsprogrammet kunde inte skapa databasen!';
$_lang["setup_database_creation_failed_note2"] = 'Installationsprogrammet kunde inte skapa databasen och ingen databas med samma namn existerar. Det är troligt att din webbhosts säkerhetsintällningar inte tillåter externa script att skapa en databas. Skapa en databas enligt webbhostens instruktioner och kör sedan installationsprogrammet igen.';
$_lang["setup_database_selection"] = 'Väljer databas `';
$_lang["setup_database_selection_failed"] = 'Val av databas misslyckades...';
$_lang["setup_database_selection_failed_note"] = 'Databasen existerar inte. Installationsprogrammet kommer att försöka skapa den.';
$_lang["snippets"] = 'Snippets';
$_lang["some_tables_not_updated"] = 'Några av tabellerna uppdaterades inte. Det här kan bero på tidigare modifikationer.';
$_lang["status_checking_database"] = 'Kontrollerar databas: ';
$_lang["status_connecting"] = ' Anslutning till värddatorn: ';
$_lang["status_failed"] = 'misslyckades!';
$_lang["status_failed_could_not_create_database"] = 'misslyckades - kunde inte skapa databas';
$_lang["status_failed_database_collation_does_not_match"] = 'misslyckades - databaskollationeringen stämmer inte; använd SET_NAMES eller välj %s';
$_lang["status_failed_table_prefix_already_in_use"] = 'misslyckades - tabellprefixet används redan!';
$_lang["status_passed"] = 'godkänd - databasen valdes';
$_lang["status_passed_database_created"] = 'godkänd - databas skapades';
$_lang["status_passed_server"] = 'godkänd - kollationeringar finns nu tillgängliga';
$_lang["strict_mode"] = ' MySQL-serverns strikt sql_mode är aktiverad!';
$_lang["strict_mode_error"] = 'Det är möjligt att vissa funktioner i MODX inte kommer att fungera som de ska om inte STRICT_TRANS_TABLES sql_mode är inaktiverat. Du kan ställa in MySQLs läge genom att redigera filen my.cnf eller genom att kontakta din serveradministratör.';
$_lang["summary_setup_check"] = 'Installationsprogrammet har gjort ett antal test för att kontrollera att allt är klart för att starta installationen.';
$_lang["system_configuration"] = 'Systemkonfiguration';
$_lang["system_configuration_validate_referer_description"] = 'Inställningen <strong>Validera HTTP_REFERER-headers</strong> rekommenderas och kan skydda din webbplats från CSRF-attacker. Vid en del serverkonfigurationer kan den dock göra hanteraren oåtkomlig.';
$_lang["table_prefix_already_inuse"] = ' - Tabellprefixet används redan i den här databasen!';
$_lang["table_prefix_already_inuse_note"] = 'Installationsprogrammet kunde inte installera i den valda databasen eftersom den redan innehåller tabeller med det prefix du angav. Ange ett nytt prefix och kör sedan installationsprogrammet igen.';
$_lang["table_prefix_not_exist"] = ' - Tabellprefixet finns inte i den här databasen!';
$_lang["table_prefix_not_exist_note"] = 'Installationsprogrammet kunde inte installera i den valda databasen eftersom den inte innehåller tabeller med det prefix du angav för uppgradering. Välj ett existerande prefix och kör sedan installationsprogrammet igen.';
$_lang["templates"] = 'Mallar';
$_lang["to_log_into_content_manager"] = 'Du kan logga in i innehållshanteraren ([+MGR_DIR+]/index.php) genom att klicka på "Stäng"-knappen.';
$_lang["toggle"] = 'Växla';
$_lang['tvs'] = 'Mallvariabler';
$_lang["unable_install_chunk"] = 'Kunde inte installera chunk.  Fil';
$_lang["unable_install_module"] = 'Kunde inte installera modul.  Fil';
$_lang["unable_install_plugin"] = 'Kunde inte installera plugin.  Fil';
$_lang["unable_install_snippet"] = 'Kunde inte installera snippet.  Fil';
$_lang["unable_install_template"] = 'Kunde inte installera mall.  Fil';
$_lang["upgrade_note"] = '<strong>Notera:</strong> Innan du börjar surfa på din webbplats bör du logga in i innehållshanteraren på ett administrativt konto och kontrollera och spara dina konfigurationsinställningar.';
$_lang["upgraded"] = 'Uppgraderad';
$_lang["validate_referer_title"] = 'Validera HTTP_REFERER-headers?';
$_lang["visit_forum"] = ', så besök <a href="http://forums.modx.com/" target="_blank">MODX forum</a>.';
$_lang["warning"] = 'VARNING!';
$_lang["welcome_message_start"] = 'Välj först vilken sorts installation som ska göras:';
$_lang["welcome_message_text"] = 'Detta program kommer att vägleda dig genom hela installationen.';
$_lang["welcome_message_welcome"] = 'Välkommen till MODX installationsprogram';
$_lang["writing_config_file"] = 'Skriver konfigurationsfil: ';
$_lang["yes"] = 'Ja';
$_lang["you_running_php"] = ' - Du kör PHP ';
?>