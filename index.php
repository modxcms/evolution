<?php
/*
*************************************************************************
	MODx Content Management System and PHP Application Framework 
	Managed and maintained by Raymond Irving, Ryan Thrash and the
	MODx community
*************************************************************************
	MODx is an opensource PHP/MySQL content management system and content
	management framework that is flexible, adaptable, supports XHTML/CSS
	layouts, and works with most web browsers, including Safari.

	MODx is distributed under the GNU General Public License	
*************************************************************************

	MODx CMS and Application Framework ("MODx")
	Copyright 2005 and forever thereafter by Raymond Irving & Ryan Thrash.
	All rights reserved.

	This file and all related or dependant files distributed with this filie
	are considered as a whole to make up MODx.

	MODx is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	MODx is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with MODx (located in "/assets/docs/"); if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	For more information on MODx please visit http://modxcms.com/
	
**************************************************************************
    Originall based on Etomite by Alex Butter
**************************************************************************
*/	

/**
 * Initialize Document Parsing
 * -----------------------------
 */
 
// get start time
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;

// secure variables from outside
$modxtags = array('@<script[^>]*?>.*?</script>@si',
                  '@&#(\d+);@e',
                  '@\[\[(.*?)\]\]@si',
                  '@\[!(.*?)!\]@si',
                  '@\[\~(.*?)\~\]@si',
                  '@\[\((.*?)\)\]@si',
                  '@{{(.*?)}}@si',
                  '@\[\*(.*?)\*\]@si');
foreach($_POST as $key => $value) {
  $_POST[$key] = preg_replace($modxtags,"", $value);
}
foreach($_GET as $key => $value) {
  $_GET[$key] = preg_replace($modxtags,"", $value);
}

$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? preg_replace("/[^A-Za-z0-9_\-\,\.\/\s]/", "", $_SERVER['HTTP_USER_AGENT']): '';
$_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? preg_replace($modxtags,"", $_SERVER['HTTP_REFERER']) : '';
if(strlen($_SERVER['HTTP_USER_AGENT'])>255) $_SERVER['HTTP_USER_AGENT'] = substr(0,255,$_SERVER['HTTP_USER_AGENT']);
if(isset($_GET['q'])) $_GET['q'] = preg_replace("/[^A-Za-z0-9_\-\.\/]/", "", $_GET['q']);

// first, set some settings, and address some IE issues
@ini_set('session.use_trans_sid', false);
@ini_set("url_rewriter.tags",'');
header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"'); // header for weird cookie stuff. Blame IE.
ob_start();
error_reporting(E_ALL);

/**
 *	Filename: index.php
 *	Function: This file loads and executes the parser. *
 */

define("IN_ETOMITE_PARSER", "true"); // provides compatibility with etomite 0.6 and maybe later versions
define("IN_PARSER_MODE", "true");
define("IN_MANAGER_MODE", "false");

// initialize the variables prior to grabbing the config file
$database_type = '';
$database_server = '';
$database_user = '';
$database_password = '';
$dbase = '';
$table_prefix = '';
$base_url = '';
$base_path = '';

// get the required includes
if($database_user=="") {
	$rt = @include_once "manager/includes/config.inc.php";
	if(!$rt) {
	echo "
<style type=\"text/css\">
*{margin:0;padding:0}
body{margin:50px;background:#eee;}
.install{padding:10px;border:5px solid #f22;background:#f99;margin:0 auto;font:120%/1em serif;text-align:center;}
p{ margin:20px 0; }
a{font-size:200%;color:#f22;text-decoration:underline;margin-top: 30px;padding: 5px;}
</style>
<div class=\"install\">
<p>MODx is not currently installed or the configuration file cannot be found.</p>
<p>Do you want to <a href=\"install/index.php\">install now</a>?</p>
</div>";
		exit;
	}
}

// start session 
startCMSSession();

// initiate a new document parser
include_once($base_path."/manager/includes/document.parser.class.inc.php");
$modx = new DocumentParser;
$etomite = &$modx; // for backward compatibility

// set some parser options
$modx->minParserPasses = 1; // min number of parser recursive loops or passes
$modx->maxParserPasses = 10; // max number of parser recursive loops or passes
$modx->dumpSQL = false;
$modx->dumpSnippets = false; // feed the parser the execution start time
$modx->tstart = $tstart;

// Debugging mode:
$modx->stopOnNotice = false;

// Don't show PHP errors to the public
if(!isset ($_SESSION['mgrValidated']) || !$_SESSION['mgrValidated']) @ini_set("display_errors","0");

// execute the parser
$modx->executeParser();
?>
