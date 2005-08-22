/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 8/20/2005 - Added documentation page support
 *  For: MODx cms (modxcms.com)
 *  Name: QuickEdit
 *  Description: Edit pages from the frontend of the site
 *  Parameters: &mod_path=Module Path (from site root);string;assets/modules/quick_edit
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

$basePath = $modx->config['base_path'];

// If we cant't find the module files...
if(!file_exists($basePath.$mod_path)) {

 // Log an error
 $error_message = '<strong>QuickEdit module not found!</strong></p><p>Edit the QuickEdit module, click the Configuration tab and change the Module Path to point to the module.</p>';
 $modx->Event->alert($error_message);
 $modx->logEvent(0, 3, $error_message, 'QuickEditor');

} else {

 $GLOBALS['quick_edit_path'] = $mod_path;
 include($basePath.$mod_path.'/editor.class.inc.php');

 $qe = new QuickEditor;
 $html = '';
 $docId = 0;
 $varId = 0;
 $modId = 0;
 $save = 0;

 if(isset($_REQUEST['doc'])) $docId = $_REQUEST['doc'];
 if(isset($_REQUEST['var'])) $varId = $_REQUEST['var'];
 if(isset($_REQUEST['id'])) $modId = $_REQUEST['id'];
 if(isset($_REQUEST['save'])) $save = $_REQUEST['save'];

 if($docId && $varId && $save) {

  $qe->save($docId, $varId);
  $qe->renderSaveAndCloseHTML();

  // This is added to keep the TP4 manager skin from rendering
  exit;

 } elseif($docId && $varId) {

  $qe->renderEditorHTML($docId, $varId, $modId);

  // This is added to keep the TP4 manager skin from rendering
  exit;

 } else {

  include($basePath.$mod_path.'/documentation.php');

 }

}
