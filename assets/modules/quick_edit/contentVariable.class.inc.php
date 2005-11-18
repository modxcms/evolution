<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 10/6/2005 - added introtext and menutitle
 *  For: MODx cms (modxcms.com)
 *  Description: Class for any template variable or page content
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

class ContentVariable {

 function contentVariable() {
  $this->id = 0;
  $this->pageId = 0;
  $this->type = '';
  $this->name = '';
  $this->caption = '';
  $this->description = '';
  $this->elements = '';
  $this->default_text = '';
  $this->content = '';
  
 }

 // Gets a content variables paramaters
 function set($var,$pageId=0) {

  global $modx;
  
  $id = 0;
  $name = '';
  $type = '';
  $caption = '';
  $description = '';
  $elements = '';
  $default_text = '';
  $value = '';

  if(!$pageId) { $pageId = $modx->documentIdentifier; }

  $tv = $modx->getTemplateVar($var, 'id, name, type, caption, description, elements, default_text', $pageId);

  // Create variables from the array
  if($tv) {

   extract($tv, EXTR_OVERWRITE);

   // This is built-in content
   if(!array_key_exists('id', $tv) && $name) {

    // Since built in content don't have ID's we'll use the name
    $id = $name;

    switch($name) {

     case 'pagetitle':
      $type = 'text';
      $caption = 'Page Title';
      $description = 'Standard short page title';
      break;

     case 'longtitle':
      $type = 'text';
      $caption = 'Long Title';
      $description = 'Standard long page title';
      break;

     case 'description':
      $type = 'textarea';
      $caption = 'Description';
      $description = 'Standard page description';
      break;

     case 'content':
      $page = $modx->getDocument($pageId,'richtext');
      if($page['richtext']) {
       $inputType = 'richtext';
      } else {
       $inputType = 'textarea';
      }
      $type = $inputType;
      $caption = 'Content';
      $description = 'Standard page content';
      break;
      
     case 'alias':
      $type = 'text';
      $caption = 'Alias';
      $description = 'Alias name';
      break;
     
     case 'published':
      $type = 'checkbox';
      $caption = 'Published';
      $description = 'Publish page';
      break;
     
     case 'introtext':
      $type = 'textarea';
      $caption = 'Intro Text';
      $description = 'Introductory text';
      break;

     case 'menutitle':
      $type = 'text';
      $caption = 'Menu Title';
      $description = 'Short menu title';
      break;
     
     default:
      $type = 'text';
      $caption = $name;
      $description = $name;

    }

   }

  }

  $this->id = $id;
  $this->pageId = $pageId;
  $this->name = $name;
  $this->type = $type;
  $this->caption = $caption;
  $this->description = $description;
  $this->elements = $elements;
  $this->default_text = $default_text;
  $this->content = $value;

 }

 // Check that the user has permission to edit this page and this TV
 function checkPermissions($userId=0) {

  global $modx;

  // Set variables
  $memberGroupsTable = $modx->getFullTableName('member_groups');
  $memberGroupAccessTable = $modx->getFullTableName('membergroup_access');
  $documentGroupsTable = $modx->getFullTableName('document_groups');
  $siteTmplVarAccessTable = $modx->getFullTableName('site_tmplvar_access');
  $pageAllowed = false;
  $varAllowed = false;
  $varId = $this->id;
  $pageId = $this->pageId;
  $modx->getSettings();
  $basepath = $modx->config['base_path'];

  if(!$userId) { $userId = $_SESSION['mgrInternalKey']; }

  // If permissions are disabled or we are the admin user
  if(!$modx->config['use_udperms'] || $userId == 1) {

   // Allow access
   $pageAllowed = true;
   $varAllowed = true;

  } else {

   if($pageId) {

    // Check permissions on the page
    $sql = "SELECT {$memberGroupsTable}.`member`
            FROM {$memberGroupsTable}
            INNER JOIN {$memberGroupAccessTable} ON `user_group` = `membergroup`
            INNER JOIN {$documentGroupsTable} ON {$memberGroupAccessTable}.`documentgroup` = {$documentGroupsTable}.`document_group`
            WHERE `member` = '{$userId}'
            AND `document` = '{$pageId}';";
    $result = $modx->db->query($sql);

    // If we have permission to this page...
    if($modx->db->getRecordCount($result)) {

     $pageAllowed = true;

    } else {

     // Get all document groups assigned to page
     $sql = "SELECT `document_group`
             FROM {$documentGroupsTable}
             WHERE `document` = '{$pageId}';";
     $result = $modx->db->query($sql);

     // If no permissions are set for the page then allow access
     if(!$modx->db->getRecordCount($result)) {
      $pageAllowed = true;
     }

    }
    
   }

   // if this is really a content variable ID...
   if(ereg('^[0-9]$',$varId)) {

    // Check permissions on the template variable
    $sql = "SELECT {$siteTmplVarAccessTable}.`id`
            FROM {$memberGroupsTable}
            INNER JOIN {$memberGroupAccessTable} ON `user_group` = `membergroup`
            INNER JOIN {$siteTmplVarAccessTable} ON {$memberGroupAccessTable}.`documentgroup` = {$siteTmplVarAccessTable}.`documentgroup`
            WHERE `member` = '{$userId}'
            AND `tmplvarid` = '{$varId}';";
    $result = $modx->db->query($sql);

    // If we have permission to this template variable...
    if($modx->db->getRecordCount($result)) {

     $varAllowed = true;

    } else {

     // Get all document groups assigned to the TV
     $sql = "SELECT `documentgroup`
             FROM {$siteTmplVarAccessTable}
             WHERE `tmplvarid` = '{$varId}';";
     $result = $modx->db->query($sql);

     // If no permissions are set for the TV then allow access
     if(!$modx->db->getRecordCount($result)) {
      $varAllowed = true;
     }

    }

   } else {

    // If the TV ID is not numeric then just check the documents permissions
    $varAllowed = true;

   }

  }

  // If we have access to the document and the TV return true, otherwise return false
  return ($pageAllowed && $varAllowed);

 }
 
 // Check that a document isn't locked for editing
 function locked($userId=0) {

  global $modx;

  $activeUsersTable = $modx->getFullTableName('active_users');
  $pageId = $this->pageId;
  $locked = true;

  if(!$userId) { $userId = $_SESSION['mgrInternalKey']; }

  $sql = "SELECT `internalKey`
          FROM {$activeUsersTable}
          WHERE (`action` = 27)
          AND `internalKey` != '{$iserId}'
          AND `id` = '{$pageId}';";
  $result = $modx->db->query($sql);

  if($modx->db->getRecordCount($result) === 0) {
   $locked = false;
  }

  return $locked;

 }
 
}

?>
