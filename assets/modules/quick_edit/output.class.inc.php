<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 10/7/05 - Added editable description
 *  For: MODx cms (modxcms.com)
 *  Description: Class for outputing QuickEdit links
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

class Output {

 function Output() {
  $this->output = '';
 }

 function mergeTags() {

  /*
   *  Replace content variables that start with a # with <quickedit> tag
   *  We can't just replace them with the links because the links would get cached if
   *  we did and that's not a good idea, trust me. This way only the custom tags get cached
   *  which is harmless enough and the links get added just before the page is rendered.
   */

  $output = &$this->output;
  $output = preg_replace('~\[\*#(.*?)\*\]~', '<quickedit:\\1 />[*\\1*]', $output);

 }

 function mergeLinks($moduleId) {

  /*
   *  If you are logged in this function will find the <quickedit:... />
   *  tags and replace them with real edit links for frontend editing.
   *  It also inserts the styles and javascript necessary to make the
   *  parent hover highlighting and editor pop-up window opening possible.
   */

  global $modx;

  $allowed = true;
  $basePath = $modx->config['base_path'];
  $modPath = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
  $output = $this->output;

  include_once($basePath.$modPath.'/module.class.inc.php');
  
  $module = new Module;
  $module->id = $moduleId;

  // If we are logged in and have edit permissions...
  if(!isset($_SESSION['mgrValidated'])) {

   $allowed = false;

  } elseif(!$modx->hasPermission('edit_document')) {

   $allowed = false;

  } elseif(!$modx->hasPermission('exec_module')) {

   $allowed = false;

  } elseif(!$module->checkPermissions()) {

   $allowed = false;

  }

  if($allowed) {

   include_once($basePath.$modPath.'/contentVariable.class.inc.php');

   $cv = new ContentVariable;
   $managerPath = $modx->getManagerPath();
   $pageId = $modx->documentIdentifier;
   $pageUrl = $modx->makeUrl($pageId);
   $logoutUrl = $modx->makeURL($pageId, '', 'QuickEdit_logout=logout');
   $replacements = array();
   $link = '';

  // Define the CSS and Javascript that we will add to the header of the page
$head = <<<EOD
<!-- Start QuickEdit headers -->
<script type="text/javascript">
 var modId = '{$moduleId}';
 var managerPath = '{$managerPath}';
</script>
<script src="{$modPath}/javascript/cookies.js" type="text/javascript"></script>
<script src="{$modPath}/javascript/output.js" type="text/javascript"></script>
<script type="text/javascript" src="manager/media/script/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="manager/media/script/scriptaculous/scriptaculous.js"></script>
<link type="text/css" rel="stylesheet" href="{$modPath}/styles/output.css" />
<!-- End QuickEdit headers -->
EOD;

$cvs = $modx->getTemplateVars('*','id, name', '', 1, 'name');
$editable = array('pagetitle', 'longtitle', 'description', 'content', 'alias', 'introtext', 'menutitle');
foreach($cvs as $content) {
 
 $cv_obj = new ContentVariable;
 if(isset($content['id'])) {
  $cv_obj->set($content['id']);	
 } else if(in_array($content['name'], $editable)) {
  $cv_obj->set($content['name']);
 }
 
 if($cv_obj->id && $cv_obj->checkPermissions()) {
  $class_name = 'QE_'.(is_numeric($cv_obj->id) ? 'TV' : 'BuiltIn');
$toolbr_cv_html .= <<<EOD
<li><a href="#" id="QE_Toolbar_{$cv_obj->id}" class="{$class_name}" onclick="javascript: QE_OpenEditor({$pageId}, '{$cv_obj->id}', {$moduleId});" title="Edit {$cv_obj->description}">{$cv_obj->caption}</a></li
>
EOD;
 }
 
}

$html_top = <<<EOD
<!-- Start QuickEdit toolbar -->

<div id="QE_Collapse_Wrapper" onmouseover="QE_Collapse(event);" onmouseout="QE_Collapse(event);" onmouseup="QE_SetPosition(this);">
<div id="QE_Expand_Wrapper" onmouseover="QE_Expand(document.getElementById('QE_menu_1'));QE_Expand(document.getElementById('QE_EditTitle'))">

<div id="QE_Toolbar">
    
    <h1 id="QE_Title">QuickEdit</h1>
    
    <div id="QE_menu_1" class="collapsed">
        <ul>
            <li><a href="#" id="QE_ShowHide" onclick="QE_ShowHideLinks(true);" title="Show and hide the QuickEdit links">Show/Hide Links</a></li
            ><li><a id="QE_Manager" href="{$managerPath}" title="Go to the MODx manager">Manager</a></li
            ><li><a id="QE_Logout" href="{$logoutUrl}" title="Logout of your manager acount">Logout</a></li
            ><li><a id="QE_Help" href="http://www.modxcms.com/quickedit.html" title="QuickEdit documentation on modxcms.com">Help</a></li
        ></ul>
    </div>
    <div id="QE_EditTitle" onmouseover="QE_Expand(document.getElementById('QE_menu_2'));" class="collapsed">
        <h1>Edit...</h1>
    </div>
    <div id="QE_menu_2" class="collapsed">
        <ul>
            {$toolbr_cv_html}</ul>
    </div>

</div>
 
</div>
</div>
<!-- End QuickEdit toolbar -->
EOD;

$html_bottom = <<<EOD
<script type="text/javascript">
QE_PositionToolbar(document.getElementById('QE_Collapse_Wrapper'));
QE_ShowHideLinks();
new Draggable('QE_Collapse_Wrapper', {handle:'QE_Title'});
</script>
EOD;

   // Get an array of the content variable names
   preg_match_all('~<quickedit:(.*?)\s*\/>~', $output, $matches);

   // Loop through every TV we found
   for($i=0; $i<count($matches[1]); $i++) {

    $contentVarName = $matches[1][$i];
    $cv->set($contentVarName, $pageId);
    $link = '';

    // Check that we have permission to edit this content
    if($cv->id && $cv->checkPermissions()) {

     // Set the HTML for the link
$link = <<<EOD
<a href="#" onclick="javascript: QE_OpenEditor({$pageId}, '{$cv->id}', {$moduleId});" onmouseover="javascript: QE_HighlightContent(this);" onmouseout="javascript: QE_UnhighlightContent(this);" title="Edit {$cv->description}" class="QE_Link">&laquo; edit {$cv->name}</a>
EOD;

    }

    $replacements[$i] = $link;

   }

   // Merge the links with the content
   $output = str_replace($matches[0], $replacements, $output);

   // If the javascript hasn't already been added to the page, do it now
   if(strpos($output, $head) === false) {
    $output = str_replace('</head>',$head.'</head>',$output);
   }
   
   // If the top html hasn't already been added to the page, do it now
   if(strpos($output, $html_top) === false) {
    $output = ereg_replace('(<body[^>]*>)', '\1'.$html_top, $output);
   }

   // If the bottom html hasn't already been added to the page, do it now
   if(strpos($output, $html_bottom) === false) {
    $output = str_replace('</body>',$html_bottom.'</body>',$output);
   }

  }

  // Remove any leftover <quickedit> tags that we may not have had permission to
  $output = preg_replace('~<quickedit:.*?\s*\/>~', '', $output);

  $this->output = $output;

 }

}

?>
