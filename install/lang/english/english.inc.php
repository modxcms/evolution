<?php
/**
 * MODx language File
 *
 * @author davaeron
 * @package MODx
 * @version 1.0
 * 
 * Filename:       /install/lang/english/english.inc.php
 * Language:       English
 * Encoding:       iso-8859-1
 */




$_lang['license'] = '<p class="title">MODx License Agreement.</p>
	    <hr style="text-align:left;height:1px;width:90%" />
		<h4>You must agree to the License before continuing installation.</h4>
		<p>Usage of this software is subject to the GPL license. To help you understand
		what the GPL licence is and how it affects your ability to use the software, we
		have provided the following summary:</p>
		<h4>The GNU General Public License is a Free Software license.</h4>
		<p>Like any Free Software license, it grants to you the four following freedoms:</p>
		<ul>
            <li>The freedom to run the program for any purpose. </li>
            <li>The freedom to study how the program works and adapt it to your needs. </li>
            <li>The freedom to redistribute copies so you can help your neighbor. </li>
            <li>The freedom to improve the program and release your improvements to the
            public, so that the whole community benefits. </li>
		</ul>
		<p>You may exercise the freedoms specified here provided that you comply with
		the express conditions of this license. The principal conditions are:</p>
		<ul>
            <li>You must conspicuously and appropriately publish on each copy distributed an
            appropriate copyright notice and disclaimer of warranty and keep intact all the
            notices that refer to this License and to the absence of any warranty; and give
            any other recipients of the Program a copy of the GNU General Public License
            along with the Program. Any translation of the GNU General Public License must
            be accompanied by the GNU General Public License.</li>

            <li>If you modify your copy or copies of the program or any portion of it, or
            develop a program based upon it, you may distribute the resulting work provided
            you do so under the GNU General Public License. Any translation of the GNU
            General Public License must be accompanied by the GNU General Public License. </li>

            <li>If you copy or distribute the program, you must accompany it with the
            complete corresponding machine-readable source code or with a written offer,
            valid for at least three years, to furnish the complete corresponding
            machine-readable source code.</li>

            <li>Any of these conditions can be waived if you get permission from the
            copyright holder.</li>

            <li>Your fair use and other rights are in no way affected by the above.
            </li>
        </ul>
		<p>The above is a summary of the GNU General Public License. By proceeding, you
		are agreeing to the GNU General Public Licence, not the above. The above is
		simply a summary of the GNU General Public Licence, and its accuracy is not
		guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU General Public
		License</a> in full before proceeding, which can also be found in the license
		file distributed with this package.</p>';
$_lang["encoding"] = 'iso-8859-1';	//charset encoding for html header
$_lang["modx_install"] = 'MODx &raquo; Install';
$_lang["loading"] = 'Loading...';
$_lang["Begin"] = 'Begin';
$_lang["status_connecting"] = ' Connection to host: ';
$_lang["status_failed"] = 'failed!';
$_lang["status_passed"] = 'passed';
$_lang["status_checking_database"] = '...    Checking database: ';
$_lang["status_failed_could_not_select_database"] = 'failed - could not select database';
$_lang["status_failed_table_prefix_already_in_use"] = 'failed - table prefix already in use!';
$_lang["welcome_message_welcome"] = 'Welcome to the MODx installation program.';
$_lang["welcome_message_text"] = 'This program will guide you through the rest of the installation.';
$_lang["welcome_message_select_begin_button"] = 'Please select the `Begin` button to get started:';
$_lang["installation_mode"] = 'Installation Mode';
$_lang["installation_new_installation"] = 'New Installation';
$_lang["installation_install_new_copy"] = 'Install a new copy of ';
$_lang["installation_install_new_note"] = 'Please note this option may overwrite any data inside your database.';
$_lang["installation_upgrade_existing"] = 'Upgrade Existing Install';
$_lang["installation_upgrade_existing_note"] = 'Upgrade your current files and database.';
$_lang["installation_upgrade_advanced"] = 'Advanced Upgrade Install<br /><small>(edit database config)</small>';
$_lang["installation_upgrade_advanced_note"] = 'For advanced database admins or moving to servers with a different database connection character set. <b>You will need to know your full database name, user, password and connection/collation details.</b>';
$_lang["connection_screen_connection_information"] = 'Connection Information';
$_lang["connection_screen_connection_and_login_information"] = 'Database connection and login information';
$_lang["connection_screen_connection_note"] = 'Please enter the name of the database created for MODX. If you there is no database yet, the installer will attempt to create a database for you. This may fail depending on the MySQL configuration or the database user permissions for your domain/installation.';
$_lang["connection_screen_database_name"] = 'Database name:';
$_lang["connection_screen_table_prefix"] = 'Table prefix:';
$_lang["connection_screen_collation"] = 'Collation:';
$_lang["connection_screen_character_set"] = 'Connection character set:';
$_lang["connection_screen_database_info"] = 'Now please enter the login data for your database.';
$_lang["connection_screen_database_host"] = 'Database host:';
$_lang["connection_screen_database_login"] = 'Database login name:';
$_lang["connection_screen_database_pass"] = 'Database password:';
$_lang["connection_screen_test_connection"] = 'Test connection';
$_lang["connection_screen_default_admin_user"] = 'Default Admin User';
$_lang["connection_screen_default_admin_note"] = 'Now you&#39;ll need to enter some details for the main administrator account. You can fill in your own name here, and a password you&#39;re not likely to forget. You&#39;ll need these to log into Admin once setup is complete.';
$_lang["connection_screen_default_admin_login"] = 'Administrator username:';
$_lang["connection_screen_default_admin_email"] = 'Administrator email:';
$_lang["connection_screen_default_admin_password"] = 'Administrator password:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Confirm password:';
$_lang["optional_items"] = 'Optional Items';
$_lang["optional_items_note"] = 'Please choose your installation options and click Install:';
$_lang["sample_web_site"] = 'Sample Web Site';
$_lang["install_overwrite"] = 'Install/Overwrite';
$_lang["sample_web_site_note"] = 'Please note that this will <b style=\"color:#CC0000\">overwrite</b> existing documents and resources.';
$_lang["checkbox_select_options"] = 'Checkbox select options:';
$_lang["all"] = 'All';
$_lang["none"] = 'None';
$_lang["toggle"] = 'Toggle';
$_lang["templates"] = 'Templates';
$_lang["install_update"] = 'Install/Update';
$_lang["chunks"] = 'Chunks';
$_lang["modules"] = 'Modules';
$_lang["plugins"] = 'Plugins';
$_lang["snippets"] = 'Snippets';
$_lang["preinstall_validation"] = 'Pre-install validation';
$_lang["summary_setup_check"] = 'Setup has carried out a number of checks to see if everything\'s ready to start the setup.';
$_lang["checking_php_version"] = "Checking PHP version: ";
$_lang["failed"] = 'Failed!';
$_lang["ok"] = 'OK!';
$_lang["you_running_php"] = ' - You are running on PHP ';
$_lang["modx_requires_php"] = ', and MODx requires PHP 4.1.0 or later';
$_lang["php_security_notice"] = '<legend>Security notice</legend><p>While MODx will work on your PHP version, usage of MODx on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. Please upgrade to PHP version is 4.3.8 or higher, which patches these holes. It is recommended you upgrade to this version for the security of your own website.</p>';
$_lang["checking_sessions"] = 'Checking if sessions are properly configured: ';
$_lang["checking_if_cache_exist"] = 'Checking if <span class=\"mono\">assets/cache</span> directory exists: ';
$_lang["checking_if_cache_writable"] = 'Checking if <span class=\"mono\">assets/cache</span> directory is writable: ';
$_lang["checking_if_cache_file_writable"] = 'Checking if <span class=\"mono\">assets/cache/siteCache.idx.php</span> file is writable: ';
$_lang["checking_if_cache_file2_writable"] = 'Checking if <span class=\"mono\">assets/cache/sitePublishing.idx.php</span> file is writable: ';
$_lang["checking_if_images_exist"] = 'Checking if <span class=\"mono\">assets/images</span> directory exists: ';
$_lang["checking_if_images_writable"] = 'Checking if <span class=\"mono\">assets/images</span> directory is writable: ';
$_lang["checking_if_export_exists"] = 'Checking if <span class=\"mono\">assets/export</span> directory exists: ';
$_lang["checking_if_export_writable"] = 'Checking if <span class=\"mono\">assets/export</span> directory is writable: ';
$_lang["checking_if_config_exist_and_writable"] = 'Checking if <span class=\"mono\">manager/includes/config.inc.php</span> exists and is writable: ';
$_lang["config_permissions_note"] = 'For new Linux/Unix installs, please create a blank file named <span class=\"mono\">config.inc.php</span> in the <span class=\"mono\">manager/includes/</span> directory with file permissions set to 0666.';
$_lang["creating_database_connection"] = 'Creating connection to the database: ';
$_lang["database_connection_failed"] = 'Database connection failed!';
$_lang["database_connection_failed_note"] = 'Please check the database login details and try again.';
$_lang["database_use_failed"] = 'Database could not be selected!';
$_lang["database_use_failed_note"] = 'Please check the database permissions for the specified user and try again.';
$_lang["checking_table_prefix"] = 'Checking table prefix `';
$_lang["table_prefix_already_inuse"] = ' - Table prefix is already in use in this database!';
$_lang["table_prefix_already_inuse_note"] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table prefix, and run Setup again.';
$_lang["table_prefix_not_exist"] = ' - Table prefix does not exist in this database!';
$_lang["table_prefix_not_exist_note"] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table prefix, and run Setup again.';
$_lang["setup_cannot_continue"] = 'Unfortunately, Setup cannot continue at the moment, due to the above ';
$_lang["error"] = 'error';
$_lang["errors"] = 'errors'; //Plural form
$_lang["please_correct_error"] = '. Please correct the error';
$_lang["please_correct_errors"] = '. Please correct the errors'; //Plural form
$_lang["and_try_again"] = ', and try again. If you need help figuring out how to fix the problem';
$_lang["and_try_again_plural"] = ', and try again. If you need help figuring out how to fix the problems'; //Plural form
$_lang["visit_forum"] = ', visit the <a href="http://www.modxcms.com/forums/" target="_blank">Operation MODx Forums</a>.';
$_lang["testing_connection"] = 'Testing connection...';
$_lang["btnback_value"] = 'Back';
$_lang["btnnext_value"] = 'Next';
$_lang["retry"] = 'Retry';
$_lang["alert_enter_database_name"] = 'You need to enter a value for database name!';
$_lang["alert_table_prefixes"] = 'Table prefixes must start with a letter!';
$_lang["alert_enter_host"] = 'You need to enter a value for database host!';
$_lang["alert_enter_login"] = 'You need to enter your database login name!';
$_lang["alert_enter_adminlogin"] = 'You need to enter a username for the system admin account!';
$_lang["alert_enter_adminpassword"] = 'You need to a password for the system admin account!';
$_lang["alert_enter_adminconfirm"] = 'The administrator password and the confirmation don\\\'t match!';
$_lang["iagree_box"] = 'I agree to the terms set out in this license.';
$_lang["btnclose_value"] = 'Close';
$_lang["running_setup_script"] = 'Running setup script... please wait';
$_lang["modx_footer1"] = '&copy; 2005-2007 the <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Mangement Framework (CMF) project. All rights reserved. MODx is licensed under the GNU GPL.';
$_lang["modx_footer2"] = 'MODx is free software.  We encourage you to be creative and make use of MODx in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODx, that you keep the source code free!';
$_lang["setup_database"] = 'Setup will now attempt to setup the database:<br />';
$_lang["setup_database_create_connection"] = 'Creating connection to the database: ';
$_lang["setup_database_create_connection_failed"] = 'Database connection failed!';
$_lang["setup_database_create_connection_failed_note"] = 'Please check the database login details and try again.';
$_lang["setup_database_selection"] = 'Selecting database `';
$_lang["setup_database_selection_failed"] = 'Database selection failed...';
$_lang["setup_database_selection_failed_note"] = 'The database does not exist. Setup will attempt to create it.';
$_lang["setup_database_creation"] = 'Creating database `';
$_lang["setup_database_creation_failed"] = 'Database creation failed!';
$_lang["setup_database_creation_failed_note"] = ' - Setup could not create the database!';
$_lang["setup_database_creation_failed_note2"] = 'Setup could not create the database, and no existing database with the same name was found. It is likely that your hosting provider\'s security does not allow external scripts to create a database. Please create a database according to your hosting provider\'s procedure, and run Setup again.';
$_lang["setup_database_creating_tables"] = 'Creating database tables: ';
$_lang["database_alerts"] = 'Database Alerts!';
$_lang["setup_couldnt_install"] = 'MODx setup couldn\'t install/alter some tables inside the selected database.';
$_lang["installation_error_occured"] = 'The following errors had occurred during installation';
$_lang["during_execution_of_sql"] = ' during the execution of SQL statement ';
$_lang["some_tables_not_updated"] = 'Some tables were not updated. This might be due to previous modifications.';
$_lang["installing_demo_site"] = 'Installing demo site: ';
$_lang["writing_config_file"] = 'Writing configuration file: ';
$_lang["cant_write_config_file"] = 'MODx couldn\'t write the config file. Please copy the following into the file ';
$_lang["cant_write_config_file_note"] = 'Once that\'s been done, you can log into MODx Admin by pointing your browser at YourSiteName.com/manager/.';
$_lang["unable_install_template"] = 'Unable to install template.  File';
$_lang["unable_install_chunk"] = 'Unable to install chunk.  File';
$_lang["unable_install_module"] = 'Unable to install module.  File';
$_lang["unable_install_plugin"] = 'Unable to install plugin.  File';
$_lang["unable_install_snippet"] = 'Unable to install snippet.  File';
$_lang["not_found"] = 'not found';
$_lang["upgraded"] = 'Upgraded';
$_lang["installed"] = 'Installed';
$_lang["running_database_updates"] = 'Running database updates: ';
$_lang["installation_successful"] = 'Installation was successful!';
$_lang["to_log_into_content_manager"] = 'To log into the Content Manager (manager/index.php) you can click on the `Close` button.';
$_lang["install"] = 'Install';
$_lang["remove_install_folder_auto"] = 'Remove the install folder and files from my website <br />&nbsp;(This operation requires delete permission to the granted to the install folder).';
$_lang["remove_install_folder_manual"] = 'Please remember to remove the &quot;<b>install</b>&quot; folder before you log into the Content Manager.';
$_lang["install_results"] = 'Install results';
$_lang["installation_note"] = '<strong>Note:</strong> After logging into the manager you should edit and save your System Configuration settings before browsing the site by choosing <strong>Administration</strong> -> System Configuration in the MODx Manager.';
$_lang["upgrade_note"] = '<strong>Note:</strong> Before browsing your site you should log into the manager with an administrative account, then review and save your System Configuration settings.';
?>