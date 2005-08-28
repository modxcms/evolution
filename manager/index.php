<?php
/*
*************************************************************************
	MODx Content Manager 
	A forked Version of Etomite 0.6 as of March 19, 2005
	Managed and maintained by Raymond Irving & Ryan Thrash
*************************************************************************
	MODx was the first major module (created by Raymond Irving Nov, 2004) to 
	work with Etomite 0.6 but will become a major overhaul of the 
	CMS-formerly-known-as-Etomite.
	
	MODx Content Manager is distributed under the GNU General Public License	
*************************************************************************

	Etomite Content Management System
	Copyright 2003, 2004 Alexander Andrew Butter

	This file and all dependant and otherwise related files are part of Etomite.

	Etomite is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	Etomite is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Etomite; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	For more information on Etomite please go to www.etomite.org

**************************************************************************
*/	


/**
 *	Filename: manager/index.php
 *	Function: This file is the main root file for MODx. It is 		
 *          only file that will be directly requested, and 		
 *          depending on the request, will branch different		
 *          content												
 */

// get start time
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime; 

// send anti caching headers
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// set error reporting
error_reporting(E_ALL ^ E_NOTICE);

// check PHP version. MODx is compatible with php 4 (4.1.0 up), extra/ improved features are planned for 5
$php_ver_comp =  version_compare(phpversion(), "4.1.0");
		// -1 if left is less, 0 if equal, +1 if left is higher
if($php_ver_comp < 0) {
	echo "Wrong php version! You're using PHP version '".phpversion()."', and MODx only works on 4.1.0 or higher."; // $_lang['php_version_check'];
	exit;
}

// set some runtime options
$incPath = str_replace("\\","/",dirname(__FILE__)."/includes/"); // Mod by Raymond
if(version_compare(phpversion(), "4.3.0")>=0) {
	set_include_path($incPath); // this now works, above code did not?
} else {
	ini_set("include_path", $incPath); // include path the old way
}

error_reporting(E_ALL ^ E_NOTICE);
@set_magic_quotes_runtime(0);

// include_once the magic_quotes_gpc workaround
include_once "quotes_stripper.inc.php"; 

// include the html_entity_decode fake function :)
if (!function_exists('html_entity_decode')) {
	function html_entity_decode ($string, $opt = ENT_COMPAT) {
		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);
		if ($opt & 1) { 
			$trans_tbl["&apos;"] = "'";
		}
		if (!($opt & 2)) {
			unset($trans_tbl["&quot;"]);
		}
		return strtr ($string, $trans_tbl);
	}
}

if (!defined("ENT_COMPAT")) define("ENT_COMPAT", 2);
if (!defined("ENT_NOQUOTES")) define("ENT_NOQUOTES", 0);
if (!defined("ENT_QUOTES")) define("ENT_QUOTES", 3);

// set the document_root :|
if(!isset($_SERVER["DOCUMENT_ROOT"]) || empty($_SERVER["DOCUMENT_ROOT"])) {
	$_SERVER["DOCUMENT_ROOT"] = str_replace($_SERVER["PATH_INFO"], "", ereg_replace("[\][\]", "/", $_SERVER["PATH_TRANSLATED"]))."/";
}


define("IN_ETOMITE_SYSTEM", "true"); // for backward compatibility with 0.6
define("IN_MANAGER_MODE", "true"); 	// we use this to make sure files are accessed through
									// the manager instead of seperately.
// include_once config file
$config_filename = "./includes/config.inc.php";
if (!file_exists($config_filename)) {
	echo "<h3>Unable to load configuration settings</h3>";
	echo "Please run the MODx <a href='../install'>install utility</a>";
	exit;
}

// include the database configuration file
include_once "config.inc.php";

// initiate the content manager class
include_once "document.parser.class.inc.php";
$modx = new DocumentParser;
$modx->loadExtension("ManagerAPI");
$modx->getSettings();
$etomite = &$modx; // for backward compatibility

// connect to the database
if(@!$modxDBConn = mysql_connect($database_server, $database_user, $database_password)) {
	die("<h2>Failed to create the database connection!</h2>. Please run the MODx <a href='../install'>install utility</a>");
} else {
	mysql_select_db($dbase);
}

// get the settings from the database
include_once "settings.inc.php";

// send the charset header
header('Content-Type: text/html; charset='.$etomite_charset);

// include version info
include_once "version.inc.php";

// accesscontrol.php checks to see if the user is logged in. If not, a log in form is shown
include_once "accesscontrol.inc.php";

// double check the session
if(!isset($_SESSION['mgrValidated'])){
	echo "Not Logged In!";
	exit;
}
// include_once the language file
if(!isset($manager_language)) {
	$manager_language = "english"; // if not set, get the english language file.
}
$_lang = array();
include_once "lang/english.inc.php";
$length_eng_lang = count($_lang);
if($manager_language!="english") {
	include_once "lang/".$manager_language.".inc.php";
}

// get the user settings from the database
include_once "user_settings.inc.php";

// check if user is allowed to access manager interface
if(isset($allow_manager_access) && $allow_manager_access==0) {
	include_once "interfacecontrol.inc.php";
}


// include_once the error handler
include_once "error.class.inc.php";
$e = new errorHandler;

// Initialize System Alert Message Queque
if (!isset($_SESSION['SystemAlertMsgQueque'])) $_SESSION['SystemAlertMsgQueque'] = array();
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

// first we check to see if this is a frameset request
if(!isset($_POST['a']) && !isset($_GET['a']) && ($e->getError()==0)) {
	// this looks to be a top-level frameset request, so let's serve up a frameset
	include_once "frames/1.php";
	exit;
}

// OK, let's retrieve the action directive from the request
if(isset($_GET['a']) && isset($_POST['a'])) {
	$e->setError(100);
	$e->dumpError();	
	// set $e to a corresponding errorcode
	// we know that if an error occurs here, something's wrong, 
	// so we dump the error, thereby stopping the script.
						
} else {
	$action=$_REQUEST['a'];
}

// save page to manager object
$modx->manager->action = $action;

// invoke OnManagerPageInit event
$modx->invokeEvent("OnManagerPageInit",array("action" => $action));


// Now we decide what to do according to the action request. This is a BIG list :)
switch ($action) {
/********************************************************************/
/* frame management - show the requested frame						*/
/********************************************************************/
	case "1" :
		// get the requested frame
		$frame=$_REQUEST['f'];
		if($frame>9) {
			$enable_debug=false; 	// this is to stop the debug thingy being attached to the framesets
		}
		include_once "frames/".$frame.".php";
	break;
/********************************************************************/
/* show the homepage												*/
/********************************************************************/
	case "2" :
		// get the home page
		include_once "header.inc.php";
		include_once "actions/static/welcome.static.action.php";
		include_once "footer.inc.php";		
	break;
/********************************************************************/
/* document data													*/
/********************************************************************/
	case "3" :
		// get the page to show document's data
		include_once "header.inc.php";
		include_once "actions/static/document_data.static.action.php";
		include_once "footer.inc.php";		
	break;
/********************************************************************/
/* content management												*/
/********************************************************************/
	case "85" :
		// get the mutate page for adding a folder
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_content.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "27" :
		// get the mutate page for changing content
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_content.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "4" :
		// get the mutate page for adding content
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_content.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "5" :
		// get the save processor
		include_once "processors/save_content.processor.php";
	break;
	case "6" :
		// get the delete processor
		include_once "processors/delete_content.processor.php";
	break;
	case "63" :
		// get the undelete processor
		include_once "processors/undelete_content.processor.php";
	break;
	case "51" :
		// get the move action
		include_once "header.inc.php";
		include_once "actions/dynamic/move_document.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "52" :
		// get the move document processor
		include_once "processors/move_document.processor.php";
	break;
	case "61" :
		// get the processor for publishing content
		include_once "processors/publish_content.processor.php";
	break;
	case "62" :
		// get the processor for publishing content
		include_once "processors/unpublish_content.processor.php";
	break;
/********************************************************************/
/* show the wait page - gives the tree time to refresh (hopefully)	*/
/********************************************************************/
	case "7" :
		// get the wait page (so the tree can reload)
		include_once "header.inc.php";
		include_once "actions/static/wait.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* let the user log out												*/
/********************************************************************/
	case "8" :
		// get the logout processor
		include_once "processors/logout.processor.php";
	break;
/********************************************************************/
/* user management													*/
/********************************************************************/
	case "87" :
		// get the new web user page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_web_user.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "88" :
		// get the edit web user page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_web_user.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "89" :
		// get the save web user processor
		include_once "processors/save_web_user.processor.php";
	break;
	case "90" :
		// get the delete web user page
		include_once "processors/delete_web_user.processor.php";
	break;
	case "11" :
		// get the new user page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_user.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "12" :
		// get the edit user page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_user.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "32" :
		// get the save user processor
		include_once "processors/save_user.processor.php";
	break;
	case "28" :
		// get the change password page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_password.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "34" :
		// get the save new password page
		include_once "processors/save_password.processor.php";
	break;
	case "33" :
		// get the delete user page
		include_once "processors/delete_user.processor.php";
	break;
/********************************************************************/
/* role management													*/
/********************************************************************/
	case "38" :
		// get the new role page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_role.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "35" :
		// get the edit role page
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_role.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
	case "36" :
		// get the save role page
		include_once "processors/save_role.processor.php";
	break;
	case "37" :
		// get the delete role page
		include_once "processors/delete_role.processor.php";
	break;
/********************************************************************/
/* template management												*/
/********************************************************************/
	case "16" :
		// get the edit template action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_templates.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "19" :
		// get the new template action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_templates.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "20" :
		// get the save processor
		include_once "processors/save_template.processor.php";
	break;
	case "21" :
		// get the delete processor
		include_once "processors/delete_template.processor.php";
	break;
	case "96" :
		// get the duplicate template processor
		include_once "processors/duplicate_template.processor.php";
	break;
/********************************************************************/
/* snippet management												*/
/********************************************************************/
	case "22" :
		// get the edit snippet action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_snippet.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "23" :
		// get the new snippet action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_snippet.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "24" :
		// get the save processor
		include_once "processors/save_snippet.processor.php";
	break;
	case "25" :
		// get the delete processor
		include_once "processors/delete_snippet.processor.php";
	break;
	case "98" :
		// get the duplicate processor
		include_once "processors/duplicate_snippet.processor.php";
	break;
/********************************************************************/
/* htmlsnippet management												*/
/********************************************************************/
	case "78" :
		// get the edit snippet action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_htmlsnippet.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "77" :
		// get the new snippet action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_htmlsnippet.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "79" :
		// get the save processor
		include_once "processors/save_htmlsnippet.processor.php";
	break;
	case "80" :
		// get the delete processor
		include_once "processors/delete_htmlsnippet.processor.php";
	break;
	case "97" :
		// get the duplicate processor
		include_once "processors/duplicate_htmlsnippet.processor.php";
	break;
/********************************************************************/
/* show the credits page											*/
/********************************************************************/
	case "18" :
		// get the credits page
		include_once "header.inc.php";
		include_once "actions/static/credits.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* empty cache & synchronisation									*/
/********************************************************************/
	case "26" :
		// get the cache emptying processor
		include_once "header.inc.php";
		include_once "actions/dynamic/refresh_site.dynamic.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* preview a page													*/
/********************************************************************/
	case "100" :
		// get the credits page
		include_once "actions/static/preview.php";
	break;
/********************************************************************/
/* Module management												*/
/********************************************************************/
	case "106" :
		// get module management
		include_once "header.inc.php";		
		include_once "actions/static/modules.static.action.php";
		include_once "footer.inc.php";
	break;
	case "107" :
		// get the new module action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_module.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "108" :
		// get the edit module action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_module.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "109" :
		// get the save processor
		include_once "processors/save_module.processor.php";
	break;
	case "110" :
		// get the delete processor
		include_once "processors/delete_module.processor.php";
	break;
	case "111" :
		// get the duplicate processor
		include_once "processors/duplicate_module.processor.php";
	break;	
	case "112" :
		// execute/run the module
		include_once "processors/execute_module.processor.php";
		break;
	case "113" :
		// get the module resources (dependencies) action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_module_resources.dynamic.action.php";
		include_once "footer.inc.php";		
	break;	
/********************************************************************/
/* plugin management												*/
/********************************************************************/
	case "101" :
		// get the new plugin action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_plugin.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "102" :
		// get the edit plugin action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_plugin.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "103" :
		// get the save processor
		include_once "processors/save_plugin.processor.php";
	break;
	case "104" :
		// get the delete processor
		include_once "processors/delete_plugin.processor.php";
	break;
	case "105" :
		// get the duplicate processor
		include_once "processors/duplicate_plugin.processor.php";
	break;
/********************************************************************/
/* view phpinfo														*/
/********************************************************************/
	case "200" :
		// show phpInfo
		phpInfo();	
	break;
/********************************************************************/
/* errorpage											*/
/********************************************************************/
	case "29" :
		// get the error page
		include_once "actions/static/error_dialog.static.action.php";
	break;
/********************************************************************/
/* file manager														*/
/********************************************************************/
	case "31" :
		// get the page to manage files
		include_once "header.inc.php";
		include_once "actions/dynamic/files.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
/********************************************************************/
/* access permissions												*/
/********************************************************************/
	case "40" :
		include_once "header.inc.php";
		include_once "actions/dynamic/access_permissions.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "91" :
		include_once "header.inc.php";
		include_once "actions/dynamic/web_access_permissions.dynamic.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* access groups processor											*/
/********************************************************************/
	case "41" : 
		include_once "processors/access_groups.processor.php";
	break; 
	case "92" : 
		include_once "processors/web_access_groups.processor.php";
	break; 
/********************************************************************/
/* settings editor													*/
/********************************************************************/
	case "17" :
		// get the settings editor
		include_once "header.inc.php";
		include_once "actions/dynamic/mutate_settings.dynamic.action.php";
		include_once "footer.inc.php";		
	break;
/********************************************************************/
/* save settings													*/
/********************************************************************/
	case "30" :
		// get the save settings processor
		include_once "processors/save_settings.processor.php";
	break;
/********************************************************************/
/* system information												*/
/********************************************************************/
	case "53" :
		// get the settings editor
		include_once "header.inc.php";
		include_once "actions/static/sysinfo.static.action.php";
		include_once "footer.inc.php";		
	break;
/********************************************************************/
/* optimise table												*/
/********************************************************************/
	case "54" :
		// get the table optimizer/truncate processor
		include_once "processors/optimize_table.processor.php";
	break;
/********************************************************************/
/* view logging														*/
/********************************************************************/
	case "13" :
		// view logging
		include_once "header.inc.php";
		include_once "actions/static/logging.static.action.php";
		include_once "footer.inc.php";		
	break;
/********************************************************************/
/* empty logs														*/
/********************************************************************/
	case "55" :
		// get the settings editor
		include_once "processors/empty_table.processor.php";
	break;
/********************************************************************/
/* calls test page														*/
/********************************************************************/
	case "999" :
		// get the test page
		include_once "header.inc.php";	
		include_once "test_page.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Empty recycle bin												*/
/********************************************************************/
	case "64" :
		// get the Recycle bin emptier
		include_once "processors/remove_content.processor.php";
	break;
/********************************************************************/
/* Messages														*/
/********************************************************************/
	case "10" :
		// get the messages page
		include_once "header.inc.php";	
		include_once "actions/static/messages.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Delete a message													*/
/********************************************************************/
	case "65" :
		// get the message deleter
		include_once "processors/delete_message.processor.php";
	break;
/********************************************************************/
/* Send a message													*/
/********************************************************************/
	case "66" :
		// get the message deleter
		include_once "processors/send_message.processor.php";
	break;	
/********************************************************************/
/* Remove locks													*/
/********************************************************************/
	case "67" :
		// get the lock remover
		include_once "processors/remove_locks.processor.php";
	break;
/********************************************************************/
/* site logging														*/
/********************************************************************/
	case "68" :
		// get the site_logging page
		include_once "header.inc.php";
		include_once "actions/static/site_logging.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* online now														*/
/********************************************************************/
	case "69" :
		// get the online_now page
		include_once "header.inc.php";
		include_once "actions/static/online_now.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Site schedule													*/
/********************************************************************/
	case "70" :
		// get the schedule page
		include_once "header.inc.php";	
		include_once "actions/static/site_schedule.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Search															*/
/********************************************************************/
	case "71" :
		// get the search page
		include_once "header.inc.php";	
		include_once "actions/static/search.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* About															*/
/********************************************************************/
	case "59" :
		// get the about page
		include_once "header.inc.php";	
		include_once "actions/static/about.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Add weblink															*/
/********************************************************************/
	case "72" :
		// get the weblink page
		include_once "header.inc.php";	
		include_once "actions/dynamic/mutate_content.dynamic.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Personal preferences												*/
/********************************************************************/
	case "74" :
		// get the preferences page
		//include_once "header.inc.php";	
		//include_once "actions/dynamic/mutate_personal_prefs.dynamic.action.php";
		//include_once "footer.inc.php";
	break;
/********************************************************************/
/* User management													*/
/********************************************************************/
	case "75" :
		include_once "header.inc.php";	
		include_once "actions/static/user_management.static.action.php";
		include_once "footer.inc.php";
	break;
	case "99" :
		include_once "header.inc.php";	
		include_once "actions/static/web_user_management.static.action.php";
		include_once "footer.inc.php";
	break;
	case "86" :
		include_once "header.inc.php";	
		include_once "actions/static/role_management.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* template/ snippet management													*/
/********************************************************************/
	case "76" :
		include_once "header.inc.php";	
		include_once "actions/static/resources.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* keywords management												*/
/********************************************************************/
	case "81" :
		include_once "header.inc.php";	
		include_once "actions/dynamic/manage_metatags.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "82" :
		include_once "processors/metatags.processor.php";
	break;
/********************************************************************/
/* Export to file													*/
/********************************************************************/
	case "83" :
		include_once "header.inc.php";	
		include_once "actions/static/export_site.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Resource Selector													*/
/********************************************************************/
	case "84" :
		include_once "actions/static/resource_selector.static.php";
	break;
/********************************************************************/
/* Backup Manager													*/
/********************************************************************/
	case "93" : 
		# header and footer will be handled interally
		include_once "actions/static/bkmanager.static.action.php";
	break;
/********************************************************************/
/* Duplicate Document													*/
/********************************************************************/
	case "94" :
		// get the duplicate processor
		include_once "processors/duplicate_content.processor.php";
	break;
/********************************************************************/
/* Import Document from file										*/
/********************************************************************/
	case "95" :
		include_once "header.inc.php";	
		include_once "actions/static/import_site.static.action.php";
		include_once "footer.inc.php";
	break;	
/********************************************************************/
/* Help																*/
/********************************************************************/
	case "9" :
		// get the help page
		include_once "header.inc.php";	
		include_once "actions/static/help.static.action.php";
		include_once "footer.inc.php";
	break;
/********************************************************************/
/* Template Variables - Based on Apodigm's Docvars 					*/
/********************************************************************/
	case "300" :
		// get the new document variable action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_tmplvars.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "301" :
		// get the edit document variable action
		include_once "header.inc.php";		
		include_once "actions/dynamic/mutate_tmplvars.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case "302" :
		// get the save processor
		include_once "processors/save_tmplvars.processor.php";
	break;
	case "303" :
		// get the delete processor
		include_once "processors/delete_tmplvars.processor.php";
	break;
	case "304" :
		// get the duplicate processor
		include_once "processors/duplicate_tmplvars.processor.php";
	break;

/********************************************************************/
/* Event viewer: show event message log		 						*/
/********************************************************************/
	case 114:
		// get event logs
		include_once "header.inc.php";	
		include_once "actions/dynamic/eventlog.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case 115:
		// get event log details viewer
		include_once "header.inc.php";	
		include_once "actions/dynamic/eventlog_details.dynamic.action.php";
		include_once "footer.inc.php";
	break;
	case 116:
		// get the event log delete processor
		include_once "processors/delete_eventlog.processor.php";
	break;
	
/********************************************************************/
/* default action: show not implemented message						*/
/********************************************************************/
	default :
		// say that what was requested doesn't do anything yet
		include_once "header.inc.php";	
		echo "
			<div class='subTitle'>
				<span class='right'><img src='media/images/_tx_.gif' width='1' height='5'><br />
				".$_lang['functionnotimpl']."</span>
			</div>
			<div class='sectionHeader'><img src='media/images/misc/dot.gif' alt='.' />&nbsp;
				".$_lang['functionnotimpl']."</div><div class='sectionBody'>
				".$_lang['functionnotimpl_message']."
			</div>
		";
		include_once "footer.inc.php";
}

/********************************************************************/
// log action, unless it's a frame request
if($action!=1 && $action!=7 && $action!=2) {
	include_once "log.class.inc.php";
	$log = new logHandler;
	$log->initAndWriteLog();
}
/********************************************************************/
// show debug
unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes
include_once "debug.inc.php";

?>