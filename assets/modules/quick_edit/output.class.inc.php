<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 10/7/05 - Added editable description
 *  Updated: 11/17/05 - Fixed href="#" and uppercase </HEAD>
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

if(!isset($_lang)) { $_lang = array(); }

class Output {

 function Output() {

  global $modx;
  global $base_path;
  global $_lang;

  $this->output = '';

  if(!$_lang) {
   $mod_path = $GLOBALS['quick_edit_path'];
   $lang = $modx->config['manager_language'];
   $qe_lang_path = $mod_path.'/lang/'.$lang.'.inc.php';
   $manager_lang_path = $base_path.'manager/includes/lang/'.$lang.'.inc.php';
   include_once($mod_path.'/lang/english.inc.php');
   if(file_exists($qe_lang_path)) { include_once($qe_lang_path); }
   include_once($manager_lang_path);
  }

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

 function mergeLinks($module_id) {

  /*
   *  If you are logged in this function will find the <quickedit:... />
   *  tags and replace them with real edit links for frontend editing.
   *  It also inserts the styles and javascript necessary to make the
   *  parent hover highlighting and editor pop-up window opening possible.
   */

  global $modx;
  global $_lang;

  $allowed = true;
  $toolbr_cv_html = '';
  $base_path = $modx->config['base_path'];
  $mod_path = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
  $output = $this->output;

  include_once($base_path.$mod_path.'/module.class.inc.php');
  
  $module = new Module;
  $module->id = $module_id;

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

   include_once($base_path.$mod_path.'/contentVariable.class.inc.php');

   $cv = new ContentVariable;
   $manager_path = $modx->getManagerPath();
   $page_id = $modx->documentIdentifier;
   $logout_url = $modx->makeURL($page_id, '', 'QuickEdit_logout=logout');
   $replacements = array();
   $link = '';

  // Define the CSS and Javascript that we will add to the header of the page
$head = <<<EOD
<!-- Start QuickEdit headers -->
<script type="text/javascript">
 var modId = '{$module_id}';
 var managerPath = '{$manager_path}';
 var QE_show_links = '{$_lang['QE_show_links']}';
 var QE_hide_links = '{$_lang['QE_hide_links']}';
</script>
<script src="{$mod_path}/javascript/cookies.js" type="text/javascript"></script>
<script src="{$mod_path}/javascript/output.js" type="text/javascript"></script>
<script type="text/javascript" src="manager/media/script/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="manager/media/script/scriptaculous/scriptaculous.js"></script>
<link type="text/css" rel="stylesheet" href="{$mod_path}/styles/output.css" />
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
<li><a href="javascript:;" id="QE_Toolbar_{$cv_obj->id}" class="{$class_name}" onclick="javascript: QE_OpenEditor({$page_id}, '{$cv_obj->id}', {$module_id});" title="{$_lang['edit']} {$cv_obj->description}">{$cv_obj->caption}</a></li
>
EOD;
 }
 
}

$html_top = <<<EOD
<!-- Start QuickEdit toolbar -->

<div id="QE_Collapse_Wrapper" onmouseover="QE_Collapse(event);" onmouseout="QE_Collapse(event);" onmouseup="QE_SetPosition(this);">
<div id="QE_Expand_Wrapper" onmouseover="QE_Expand(document.getElementById('QE_menu_1'));QE_Expand(document.getElementById('QE_EditTitle'))">

<div id="QE_Toolbar">
    
    <h1 id="QE_Title">{$_lang['QE_title']}</h1>
    
    <div id="QE_menu_1" class="collapsed">
        <ul>
            <li><a href="javascript:;" id="QE_ShowHide" onclick="QE_ShowHideLinks(true);"></a></li
            ><li><a id="QE_Manager" href="{$manager_path}">{$_lang['manager']}</a></li
            ><li><a id="QE_Logout" href="{$logout_url}">{$_lang['logout']}</a></li
            ><li><a id="QE_Help" href="http://www.modxcms.com/quickedit.html">$_lang[help]</a></li
        ></ul>
    </div>
    <div id="QE_EditTitle" onmouseover="QE_Expand(document.getElementById('QE_menu_2'));" class="collapsed">
        <h1>{$_lang['edit']}...</h1>
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
   $matches= array();
   preg_match_all('~<quickedit:(.*?)\s*\/>~', $output, $matches);

   // Loop through every TV we found
   for($i=0; $i<count($matches[1]); $i++) {

    $contentVarName = $matches[1][$i];
    $cv->set($contentVarName, $page_id);
    $link = '';

    // Check that we have permission to edit this content
    if($cv->id && $cv->checkPermissions()) {

     // Set the HTML for the link
$link = <<<EOD
<a href="javascript:;" onclick="javascript: QE_OpenEditor({$page_id}, '{$cv->id}', {$module_id});" onmouseover="javascript: QE_HighlightContent(this);" onmouseout="javascript: QE_UnhighlightContent(this);" title="Edit {$cv->description}" class="QE_Link">&laquo; {$_lang['edit']} {$cv->name}</a>
EOD;

    }

    $replacements[$i] = $link;

   }

   // Merge the links with the content
   $output = str_replace($matches[0], $replacements, $output);

   // If the javascript hasn't already been added to the page, do it now
   if(strpos($output, $head) === false) {
    $output = str_ireplace('</head>',$head.'</head>',$output);
   }
   
   // If the top html hasn't already been added to the page, do it now
   if(strpos($output, $html_top) === false) {
    $output = preg_replace('/(<body[^>]*>)/i', '\1'.$html_top, $output);
   }

   // If the bottom html hasn't already been added to the page, do it now
   if(strpos($output, $html_bottom) === false) {
    $output = str_ireplace('</body>',$html_bottom.'</body>',$output);
   }

  }

  // Remove any leftover <quickedit> tags that we may not have had permission to
  $output = preg_replace('~<quickedit:.*?\s*\/>~', '', $output);

  $this->output = $output;

 }

}

?>
