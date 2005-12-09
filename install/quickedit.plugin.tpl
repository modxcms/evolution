/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 11/27/2005 - Added support for show Manager & Help links option
 *  Updated: 12/05/2005 - Added support for editable fields as a module configuration option
 *  For: MODx cms (modxcms.com)
 *  Name: QuickEdit
 *  Description: Renders QuickEdit links in the frontend
 *  Shared parameters from: QuickEdit module
 *  Events: OnParseDocument, OnWebPagePrerender
 */

/*
                             License

QuickEdit - A MODx module which allows the editing of content via
            the frontent of the site
Copyright (C) 2005  Adam Crownoble

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Set configuration variables if not already set
if(!isset($mod_path)) { $mod_path = 'assets/modules/quick_edit'; }
if(!isset($show_manager_link)) { $show_manager_link = 1; }
if(!isset($show_help_link)) { $show_help_link = 1; }
if(!isset($editable)) { $editable = 'pagetitle,longtitle,description,content,alias,introtext,menutitle,published,hidemenu'; }

// If we can't find the module files...
if(!file_exists($mod_path)) {

 // Only log the error if we haven't already logged it...
 if(!isset($GLOBALS['quick_edit_not_found_sent'])) {

  // Set a global variable so that we can only log this once
  $GLOBALS['quick_edit_not_found_sent'] = true;

  // Log an error
  $error_message = '<strong>QuickEdit module not found!</strong></p><p>Edit the QuickEdit module, click the Configuration tab and change the Module Path to point to the module.</p>';
  $modx->logEvent(0, 3, $error_message, 'QuickEditor');

 }

} else {

 // Set globals from QE Module's shared paramaters so we can get them from the frontend
 $GLOBALS['qe_show_manager_link'] = $show_manager_link;
 $GLOBALS['qe_show_help_link'] = $show_help_link;
 $GLOBALS['qe_editable'] = $editable;

 // Set the mod_path as a global variable
 $GLOBALS['quick_edit_path'] = $mod_path;
 include_once($mod_path.'/output.class.inc.php');

 $outputObject = new Output;

 switch($modx->Event->name) {

  case 'OnParseDocument' :

   $outputObject->output = $modx->documentOutput;

   // Merge QuickEdit comment into the output
   $outputObject->mergeTags();

   break;

  case 'OnWebPagePrerender' :

   $outputObject->output = &$modx->documentOutput;

   include_once($mod_path.'/module.class.inc.php');
   $module = new Module;
   $module->getIdFromDependentPluginName($modx->Event->activePlugin);

   // Replace QuickEdit comments with QuickEdit links
   $outputObject->mergeLinks($module->id);

   break;

 }

 // Set the event output
 $modx->documentOutput = $outputObject->output;

 // Logout ?
 $qe_logout= (isset($_GET['QuickEdit_logout'])? $_GET['QuickEdit_logout']: '');
 if($qe_logout == 'logout') {
  $_SESSION = array();
 }

}
