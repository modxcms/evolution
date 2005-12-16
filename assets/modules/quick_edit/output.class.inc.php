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

  $lang = $modx->config['manager_language'];
  $qe_path = $base_path.'/'.$GLOBALS['quick_edit_path'];

  $this->output = '';
  $this->checked_image = "<img src=\"{$GLOBALS['quick_edit_path']}/images/checked.gif\" alt=\"checked\" style=\"float:left; margin-right:3px;\" />";
  $this->unchecked_image = "<img src=\"{$GLOBALS['quick_edit_path']}/images/unchecked.gif\" alt=\"checked\" style=\"float:left; margin-right:3px;\" />";

  // Combine QE language files with manager language files (manager should override QE)
  $qe_eng_path = $qe_path.'/lang/'.$lang.'.inc.php';
  $qe_lang_path = $qe_path.'/lang/'.$lang.'.inc.php';
  $manager_lang_path = $base_path.'manager/includes/lang/'.$lang.'.inc.php';
  $lang_set = isset($_lang);
  include_once($qe_eng_path);
  if(file_exists($qe_lang_path)) { include_once($qe_lang_path); }
  if(!$lang_set) { include_once($manager_lang_path); }

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
  global $show_manager_link;
  global $show_help_link;

  $show_manager_link = $GLOBALS['qe_show_manager_link'];
  $show_help_link = $GLOBALS['qe_show_help_link'];
  $editable = explode(',',$GLOBALS['qe_editable']);
  $manager_link = '';
  $help_link = '';
  $allowed = true;
  $toolbar_cv_html = '';
  $base_path = $modx->config['base_path'];
  $qe_path = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
  $output = $this->output;

  include_once($base_path.$qe_path.'/module.class.inc.php');
  
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

   include_once($base_path.$qe_path.'/contentVariable.class.inc.php');

   $cv = new ContentVariable;
   $manager_path = $modx->getManagerPath();
   $doc_id = $modx->documentIdentifier;
   $logout_url = $modx->makeURL($doc_id, '', 'QuickEdit_logout=logout');
   $replacements = array();
   $link = '';
   $type_image = '';
   $on_click = '';
   $change_value = '';
   $menus = array('content'=>array(), 'setting'=>array(), 'go'=>array());

$menus['setting'][] = <<<EOD
<a href="javascript:;" id="QE_ShowLinks" onclick="QE_ToggleLinks(true);"><img id="QE_ShowLinks_check" src="{$GLOBALS['quick_edit_path']}/images/checked.gif" alt="checked" style="float:left; margin-right:3px;" />{$_lang['QE_show_links']}</a>
EOD;

if($show_manager_link) {
$menus['go'][] = <<<EOD
<a id="QE_Manager" href="{$manager_path}">{$_lang['manager']}</a>
EOD;
}

if($show_help_link) {
$menus['go'][] = <<<EOD
<a id="QE_Help" href="http://www.modxcms.com/quickedit.html">{$_lang['help']}</a>
EOD;
}

$menus['go'][] = <<<EOD
<a id="QE_Logout" href="{$logout_url}">{$_lang['logout']}</a>
EOD;

$cvs = $modx->getTemplateVars('*','id, name', '', 1, 'name');

foreach($cvs as $content) {

 $cv_obj = new ContentVariable;
 if(isset($content['id'])) {
  $cv_obj->set($content['id']);	
 } else if(in_array($content['name'], $editable)) {
  $cv_obj->set($content['name']);
 }

 if($cv_obj->id && $cv_obj->checkPermissions()) {

  // Get the menu
  $menu = $cv_obj->group;

  // Check for special CV types //

  // One checkbox
  if($cv_obj->type == 'checkbox' && !strpos($cv_obj->elements,'||')) {
   $type_image = ($cv_obj->content ? $this->checked_image : $this->unchecked_image);
   $change_value = ($cv_obj->content ? '' : (strpos($cv_obj->elements,'==') ? substr(strstr($cv_obj->elements,'=='), 2) : $cv_obj->elements));
$menus[$menu][] .= <<<EOD
<a href="javascript:;" id="QE_Toolbar_{$cv_obj->id}" onclick="javascript: QE_SendAjax('doc={$doc_id}&var={$cv_obj->id}&save=1&tv{$cv_obj->name}={$change_value}', function() { window.location.reload() } );" title="{$_lang['edit']} {$cv_obj->description}">{$type_image}{$cv_obj->caption}</a>
EOD;

  // Everything else
  } else {
$menus[$menu][] .= <<<EOD
<a href="javascript:;" id="QE_Toolbar_{$cv_obj->id}" onclick="javascript: QE_OpenEditor({$doc_id}, '{$cv_obj->id}');" title="{$_lang['edit']} {$cv_obj->description}">{$cv_obj->caption}</a>
EOD;

  }

 }
 
}

foreach($menus as $menu_name=>$links) {

 $links_html = '';

 foreach($links as $link) {
  $links_html .= "<li>{$link}</li>";
 }

$buttons_html .= <<<EOD
<a id="QE_Button_{$menu_name}" class="QE_Button" href="javascript:;" onclick="javascript: QE_ToggleMenu('{$menu_name}');">{$_lang[$menu_name]}</a>
EOD;

$menus_html .= <<<EOD
<div id="QE_Menu_{$menu_name}" class="QE_Menu" style="display:none;">
 <ul>
  {$links_html}
 </ul>
</div>
EOD;

}

  // Define the CSS and Javascript that we will add to the header of the page
$head = <<<EOD
<!-- Start QuickEdit headers -->
<script type="text/javascript">
 var modId = '{$module_id}';
 var managerPath = '{$manager_path}';
 var modPath = '{$qe_path}';
</script>
<script src="{$qe_path}/javascript/cookies.js" type="text/javascript"></script>
<script src="{$qe_path}/javascript/output.js" type="text/javascript"></script>
<script type="text/javascript" src="manager/media/script/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="manager/media/script/scriptaculous/scriptaculous.js"></script>
<link type="text/css" rel="stylesheet" href="{$qe_path}/styles/output.css" />
<!-- End QuickEdit headers -->
EOD;

$html_top = <<<EOD
<!-- Start QuickEdit toolbar -->
<div id="QE_Toolbar" onmouseup="QE_SetPosition(this);" style="display:none;">
 <div id="QE_Toolbar_Header">
  <h1 id="QE_Title">{$_lang['QE_title']}</h1>
  {$buttons_html}
 </div> 
 {$menus_html}
</div>
<!-- End QuickEdit toolbar -->
EOD;

$html_bottom .= <<<EOD
<script type="text/javascript">
QE_PositionToolbar($('QE_Toolbar'));
QE_ToggleLinks();
new Draggable('QE_Toolbar', {handle:'QE_Title'});
</script>
EOD;

   // Get an array of the content variable names
   $matches= array();
   preg_match_all('~<quickedit:(.*?)\s*\/>~', $output, $matches);

   // Loop through every TV we found
   for($i=0; $i<count($matches[1]); $i++) {

    $contentVarName = $matches[1][$i];
    $cv->set($contentVarName, $doc_id);
    $link = '';

    // Check that we have permission to edit this content
    if($cv->id && $cv->checkPermissions()) {

     // Set the HTML for the link
$link = <<<EOD
<a href="javascript:;" onclick="javascript: QE_OpenEditor({$doc_id}, '{$cv->id}', {$module_id});" onmouseover="javascript: QE_HighlightContent(this);" onmouseout="javascript: QE_UnhighlightContent(this);" title="Edit {$cv->description}" class="QE_Link" style="display:none;">&laquo; {$_lang['edit']} {$cv->name}</a>
EOD;

    }

    $replacements[$i] = $link;

   }

   // Merge the links with the content
   $output = str_replace($matches[0], $replacements, $output);

   // If the javascript hasn't already been added to the page, do it now
   if(strpos($output, $head) === false) {
    $output = preg_replace('~(</head>)~i', $head.'\1', $output);
   }
   
   // If the top html hasn't already been added to the page, do it now
   if(strpos($output, $html_top) === false) {
    $output = preg_replace('~(<body[^>]*>)~i', '\1'.$html_top, $output);
   }

   // If the bottom html hasn't already been added to the page, do it now
   if(strpos($output, $html_bottom) === false) {
    $output = preg_replace('~(</body>)~i',$html_bottom.'\1',$output);
   }

  }

  // Remove any leftover <quickedit> tags that we may not have had permission to
  $output = preg_replace('~<quickedit:.*?\s*\/>~', '', $output);

  $this->output = $output;

 }

}

?>
