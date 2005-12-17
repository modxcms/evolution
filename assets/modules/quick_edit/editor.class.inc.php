<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Modified: 10/24/2005 - Removed links hrefs
 *  For: MODx cms (modxcms.com)
 *  Description: Class for the QuickEditor
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

class QuickEditor {

 function QuickEditor() {

  include_once('contentVariable.class.inc.php');

  global $modx;
  global $base_path;
  global $qe_path;
  global $_lang;

  $this->output = '';

  // Combine QE language files with manager language files (manager should override QE)
  $lang = $modx->config['manager_language'];
  $qe_path = $base_path.'/'.$GLOBALS['quick_edit_path'];
  $qe_eng_path = $qe_path.'/lang/'.$lang.'.inc.php';
  $qe_lang_path = $qe_path.'/lang/'.$lang.'.inc.php';
  $manager_lang_path = $base_path.'manager/includes/lang/'.$lang.'.inc.php';
  $lang_set = isset($_lang);
  include_once($qe_eng_path);
  if(file_exists($qe_lang_path)) { include_once($qe_lang_path); }
  if(!$lang_set) { include_once($manager_lang_path); }

 }

 function renderEditorHTML($doc_id, $var_id, $mod_id) {

  /*
   *  This code generates a page meant for editing a single template
   *  variable or the pagetitle, longtitile, description or content of a
   *  page from the frontend of a website. It is meant to be used with the
   *  mergeFrontendEditableLinks() function.
   *  This page should be able to handle any type of TV on any page.
   */

  global $modx;
  global $_lang;

  $base_path = $modx->config['base_path'];
  $qe_path = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
  $snapshot_compatible_editors = array('fckeditor'); // Rich Text Editors that are supported by apply/revert code (lower-case)

  include_once($base_path.'manager/includes/tmplvars.inc.php');
  include_once($base_path.'manager/includes/tmplvars.commands.inc.php');
  include_once($base_path.'manager/includes/tmplvars.format.inc.php');

  $qe_path = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
  $cv = new ContentVariable;
  $cv->set($var_id, $doc_id);

  // PSUEDO CONSTANTS
  $module_exec_action = 112;

  $editor_html = '';
  $allowed = true;

  if(!$modx->hasPermission('edit_document')) {

   $modx->event->alert($_lang['access_permission_denied']);
   $allowed = false;
   
  } elseif(!$cv->checkPermissions()) {
   
   $modx->event->alert($_lang['access_permission_denied']);
   $allowed = false;
   
  } elseif(!$cv->id) {

   // Mage sure the the content variable exists
   $modx->event->alert($_lang['QE_cant_find_content']);
   $allowed = false;

  } elseif(!$cv->checkPermissions()) {

   // Make sure we have permission to edit it
   $modx->event->alert($_lang['access_permission_denied']);
   $allowed = false;

  } elseif($cv->locked()) {

   // Make sure the document isn't locked
   $modx->event->alert($_lang['QE_someone_editing']);
   $allowed = false;
   
  }

  if($allowed) {

   $perms = $_SESSION['mgrPermissions'];

   $modx->db->connect();

   // HTML PREP
   if($cv->type == 'richtext') {

    // invoke OnRichTextEditorInit event
    $event_output = $modx->invokeEvent("OnRichTextEditorInit", array('editor'=>$modx->config['which_editor'], 'elements'=>array('tv'.$cv->name)));

    if(is_array($event_output)) {
     $editor_html = implode("",$event_output);
    }

   }

   $tv_html = renderFormElement($cv->type, $cv->name, $cv->default_text, $cv->elements, $cv->content);

   // Set the buttons for this TV
$buttons = <<<EOD
<a id="close" href="javascript: close();"><img src="../{$qe_path}/images/close.gif" alt="{$_lang['close']}" /> {$_lang['close']}</a>

<a href="javascript: $('tv_form').submit();"><img src="../{$qe_path}/images/ok.gif" alt="{$_lang['ok']}" /> {$_lang['ok']}</a>
EOD;

   if($cv->type != 'richtext' || in_array(strtolower($modx->config['which_editor']), $snapshot_compatible_editors)) {
$buttons .= <<<EOD
<a href="javascript: applyChanges($('tv_form'));"><img src="../{$qe_path}/images/apply.gif" alt="{$_lang['apply']}" /> {$_lang['apply']}</a>
<a href="javascript: if(confirm('{$_lang['revert_prompt']}')) { saveSnapshot(); }"><img src="../{$qe_path}/images/revert.gif" alt="{$_lang['revert']}" /> {$_lang['revert']}</a>
EOD;
   }

   // Get the name of the TV
   if(!($tv_desc = $cv->description)) {
   } elseif(!($tv_desc = $cv->caption)) {
   } else {	$tv_desc = $cv->name; }

   // HTML
$html = <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$_lang['QE_lang']}" xml:lang="{$_lang['QE_xml_lang']}">
<head>

<meta http-equiv="Content-Type" content="text/html; charset={$_lang['QE_charset']}" />
<meta name="description" content="{$_lang['QE_description']}" />

<title>{$_lang['QE_title']}</title>

<link type="text/css" rel="stylesheet" href="../{$qe_path}/styles/editor.css" />
<script type="text/javascript">
var QE_ModuleActionId = '{$module_exec_action}';
var QE_ModuleId = '{$mod_id}';
var QE_FormSnapshot = '';
</script>
<script type="text/javascript" src="media/script/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="media/script/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="media/script/datefunctions.js"></script>
<script type="text/javascript" src="../{$qe_path}/javascript/editor.js"></script>

</head>
<body onload="takeSnapshot($('tv_form'));">

<form id="tv_form" class="{$cv->type}" name="mutate" method="post" enctype="multipart/form-data" action="index.php">
<input type="hidden" name="a" value="{$module_exec_action}" />
<input type="hidden" name="id" value="{$mod_id}" />
<input type="hidden" name="doc" value="{$doc_id}" />
<input type="hidden" name="var" value="{$var_id}" />
<input id="save" type="hidden" name="save" value="1" />
<input type="hidden" name="variablesmodified" value="">

<div id="toolbar">

{$buttons}

</div>

<div id="info" onclick="javascript: showDescription()">
 <h1>{$cv->caption}</h1>
 <div id="tv_description" style="display:none;"><div id="tv_description_text">{$cv->description}</div></div>
</div>

<div id="tv_container">

{$tv_html}

</div>

<button type="submit" style="display:none;"></button>

</form>

$editor_html

<script type="text/javascript">

 // Resize the window to fit the TV
 fitWindow();
 var QE_ContentVariableID = 'tv{$cv->name}';

</script>

</body>
</html>
EOD;

   echo($html);
   
  }

 }

 function save($doc_id, $var_id) {

 /*
  *  Written by Adam Crownoble (adam@obledesign.com) 7/30/2005
  *  This page saves a template variable or the pagetitle, longtitle,
  *  description or content that was edited through the frontend editor.
  *  After saving the content, it runs javascript to automatically
  *  refresh the parent window and close the pop-up edit window.
  */

  global $modx;

  $qe_path = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
  $editable = explode(',',$GLOBALS['qe_editable']);
  $db = $modx->db->config['dbase'];
  $pre = $modx->db->config['table_prefix'];
  $html = '';
  $result = null;
  $allowed = false;

  $cv = new ContentVariable;
  $cv->set($var_id, $doc_id);

  if($modx->hasPermission('save_document') && $cv->checkPermissions()) {

   if(!isset($caption) || $caption =="") {
    $caption  = (isset($name) && !empty($name)) ? $name:"Untitled Variable";
   }

   // Get the template variable value
   foreach($_POST as $post_key=>$post_value) {
    if(substr($post_key, 0, 2) == 'tv') {
     $value = $post_value;
    }
   }

   if(is_array($value)) {
    $value = implode('||', $value);
   }

   $value_prep = $modx->db->escape($value);

   if(is_numeric($cv->id)) {

    // Define the tmplvars vairable by reference for plugin support
    $tmplvars[$cv->id] = &$value_prep;
    // invoke OnBeforeDocFormSave event
    $modx->invokeEvent('OnBeforeDocFormSave', array('mode'=>'upd', 'id'=>$doc_id));

    $sql = "SELECT id
            FROM {$db}.`{$pre}site_tmplvar_contentvalues`
            WHERE `tmplvarid` = '{$cv->id}'
            AND `contentid` = '{$doc_id}';";
    $result = $modx->db->query($sql);

    if($modx->db->getRecordCount($result)) {

     $sql = "UPDATE {$db}.`{$pre}site_tmplvar_contentvalues`
             SET `value` = '{$value_prep}'
             WHERE `tmplvarid` = '{$cv->id}'
             AND `contentid` = '{$doc_id}';";

    } else {

     $sql = "INSERT INTO {$db}.`{$pre}site_tmplvar_contentvalues`(tmplvarid, contentid, value)
             VALUES('{$cv->id}', '{$doc_id}', '{$value_prep}');";
             
    }

   } elseif(in_array($cv->id, $editable)) {

    // Define vairable with the content id as it's name by reference for plugin support
    $cv_id = $cv->id;
    $$cv_id = &$value_prep;
    // invoke OnBeforeDocFormSave event
    $modx->invokeEvent('OnBeforeDocFormSave', array('mode'=>'upd', 'id'=>$doc_id));

    $sql = "UPDATE {$db}.`{$pre}site_content`
            SET `{$cv->id}` = '{$value_prep}'
            WHERE `id` = '{$doc_id}';";

   }

   if($sql) { $result = $modx->db->query($sql); }

   if(!$result){

    $modx->logEvent(0, 0, "<p>Save failed!</p><strong>SQL:</strong><pre>{$sql}</pre>", 'QuickEditor');

   } else {

    // invoke OnDocFormSave event
    $modx->invokeEvent('OnDocFormSave', array('mode'=>'upd', 'id'=>$doc_id));

    // empty cache
    include_once($modx->config['base_path'] . '/manager/processors/cache_sync.class.processor.php');
    $sync = new synccache();
    $sync->setCachepath("../assets/cache/");
    $sync->setReport(false);
    $sync->emptyCache(); // first empty the cache

   }

  }

 }

 // After submitting this will generate a page that will
 function renderSaveAndCloseHTML() {

  $modPath = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences

$html = <<<EOD
<html>
<head>

<title>Click to close</title>

<script language="JavaScript" src="../{$modPath}/javascript/editor.js"></script>
<script type="text/javascript">
 reloadAndClose();
</script>

</head>
<body>

<p style="margin-top:20px; text-align:center;"><a href="javascript: postSave();">Close window</a></p>

</body>
</html>
EOD;

  echo($html);

 }

}

?>
