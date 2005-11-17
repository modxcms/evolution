<?php


/*
*************************************************************************
	MODx Content Manager 
	A forked Version of Etomite 0.6 as of March 19, 2005
	Managed and maintained by Raymond Irving, Ryan Thrash and the MODx community
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
 * Initialize Document Parsing
 * -----------------------------
 */
 
// get start time
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;

// first, set some settings, and do some stuff
@ini_set('session.use_trans_sid', false);
@ini_set("url_rewriter.tags","");
header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"'); // header for weird cookie stuff. Blame IE.
ob_start();
error_reporting(E_ALL);

/**
 *	Filename: index.php
 *	Function: This file loads and executes the parser. 
 *
 */

define("IN_ETOMITE_PARSER", "true"); // for backward compatibility with etomite .6
define("IN_PARSER_MODE", "true");

// Added by Remon
define("IN_MANAGER_MODE", "false");


// set these values here for a small speed increase! :)
$database_type = "";
$database_server = "";
$database_user = "";
$database_password = "";
$dbase = "";
$table_prefix = "";		
$base_url = "";
$base_path = "";

// get the required includes
if($database_user=="") {
	$rt = @include_once "manager/includes/config.inc.php";
	if(!$rt) {
		echo "<h3>Unable to load configuration settings</h3>";
		echo "Please run the MODx <a href='install'>install utility</a>";
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
$modx->minParserPasses = 1;		// min number of parser recursive loops or passes
$modx->maxParserPasses = 10;	// max number of parser recursive loops or passes
$modx->dumpSQL = false;
$modx->dumpSnippets = false;
$modx->tstart = $tstart;	// feed the parser the execution start time

// Added by Remon
// Debugging mode:
$modx->stopOnNotice = false;
						
// execute the parser
$modx->executeParser();


?>