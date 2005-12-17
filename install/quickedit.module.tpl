/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 8/20/2005 - Added documentation page support
 *  Updated: 11/27/2005 - Added show Manager & Help link options and apply (AJAX saving)
 *  Updated: 12/05/2005 - Added editable fields as configuration option
 *  For: MODx cms (modxcms.com)
 *  Name: QuickEdit
 *  Description: Edit pages from the frontend of the site
 *  Parameters: &mod_path=Module Path (from site root);string;assets/modules/quick_edit &show_manager_link=Show Manager Link;int;1 &show_help_link=Show Help Link;int;1 &editable=Editable Fields;string;pagetitle,longtitle,description,content,alias,introtext,menutitle,published,hidemenu,menuindex,searchable,cacheable
 *  Parameter sharing: enabled
 *  Dependencies: QuickEdit plugin
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
if(!isset($editable)) { $editable = 'pagetitle,longtitle,description,content,alias,introtext,menutitle,published,hidemenu,menuindex,searchable,cacheable'; }

$basePath = $modx->config['base_path'];

// If we cant't find the module files...
if(!file_exists($basePath.$mod_path)) {

 // Log an error
 $error_message = '<strong>QuickEdit module not found!</strong></p><p>Edit the QuickEdit module, click the Configuration tab and change the Module Path to point to the module.</p>';
 $modx->Event->alert($error_message);
 $modx->logEvent(0, 3, $error_message, 'QuickEditor');

} else {

 $GLOBALS['qe_editable'] = $editable;
 $GLOBALS['quick_edit_path'] = $mod_path;
 include($basePath.$mod_path.'/editor.class.inc.php');

 $qe = new QuickEditor;
 $html = '';
 $doc_id = 0;
 $var_id = 0;
 $mod_id = 0;
 $save = 0;
 $ajax = 0;
 $apply = 0;

 if(isset($_REQUEST['doc'])) $doc_id = $_REQUEST['doc'];
 if(isset($_REQUEST['var'])) $var_id = $_REQUEST['var'];
 if(isset($_REQUEST['id'])) $mod_id = $_REQUEST['id'];
 if(isset($_REQUEST['save'])) $save = $_REQUEST['save'];
 if(isset($_REQUEST['ajax'])) $ajax = $_REQUEST['ajax'];

 if($doc_id && $var_id && $save && $ajax) {

  $qe->save($doc_id, $var_id);

 } elseif($doc_id && $var_id && $save) {

  $qe->save($doc_id, $var_id);
  $qe->renderSaveAndCloseHTML();

 } elseif($doc_id && $var_id) {

  $qe->renderEditorHTML($doc_id, $var_id, $mod_id);

 } else {

  include($basePath.$mod_path.'/documentation.php');

 }

}
