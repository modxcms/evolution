<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
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
   $replacements = array();
   $link = '';

  // Define the CSS and Javascript that we will add to the header of the page
$head = <<<EOD

<!-- Start QuickEdit headers -->
<script type="text/javascript">
 var modId = '{$moduleId}';
 var managerPath = '{$managerPath}';
</script>
<script src="{$modPath}/output.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="{$modPath}/output.css" />
<!-- End QuickEdit headers -->

EOD;

   // Get an array of the content variable names
   preg_match_all('~<quickedit:(.*?)\s*\/>~', $output, $matches);

   // Loop through every TV we found
   for($i=0; $i<count($matches[1]); $i++) {

    $contentVarName = $matches[1][$i];
    $cv->set($contentVarName, $pageId);
    $link = '';

    // Check that we have permission to edit this content
    if($cv->checkPermissions()) {

     // Set the HTML for the link
$link = <<<EOD
<a onclick="javascript: openEditor({$pageId}, '{$cv->id}', {$moduleId});" onmouseover="javascript: highlightContent(this);" onmouseout="javascript: unhighlightContent(this);" title="Edit {$cv->description}" class="QuickEditLink">&laquo; edit {$cv->name}</a>
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

  }

  // Remove any leftover <quickedit> tags that we may not have had permission to
  $output = preg_replace('~<quickedit:.*?\s*\/>~', '', $output);

  $this->output = $output;

 }

}

?>
