<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Class for a module
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

class Module{

 function Module() {
  $this->id = 0;
 }

 function getIdFromDependentPluginName($name) {

  global $modx;

  $pluginTypeKey = 30;
  $siteModuleDepObjTable = $modx->getFullTableName('site_module_depobj');
  $sitePluginsTable = $modx->getFullTableName('site_plugins');
  $sql = "SELECT {$siteModuleDepObjTable}.`module`
          FROM {$siteModuleDepObjTable}
          INNER JOIN {$sitePluginsTable} ON {$siteModuleDepObjTable}.`resource` = {$sitePluginsTable}.`id`
          WHERE {$sitePluginsTable}.`name` = '{$name}'
          AND {$siteModuleDepObjTable}.`type` = '{$pluginTypeKey}';";
  $ids = array();

  if($result = $modx->db->query($sql)) {

   while($row = $modx->db->getRow($result)) {
    $ids[] = $row['module'];
   }

  }

  if(count($ids) == 1) {
   $this->id = $ids[0];
  }

 }
 
 function getUserGroups() {

  global $modx;

  $groups = array();
  $modId = $this->id;
  $siteModuleAccessTable = $modx->getFullTableName('site_module_access');

	$sql = "SELECT usergroup
          FROM {$siteModuleAccessTable}
          WHERE module = '{$modId}';";
	$result = $modx->db->query($sql);

  while($row = $modx->db->getRow($result)) {
   $groups[] = $row['usergroup'];
  }

  return $groups;

 }

 function checkPermissions($userId=0) {

  global $modx;
  
  $allowed = false;

  if(!$userId) { $userId = $_SESSION['mgrInternalKey']; }
  
  if(!$modx->config['use_udperms'] || $_SESSION['mgrRole'] == 1) {

   $allowed = true;

  } else {

   $groups = $this->getUserGroups();
   
   if(count($groups) == 0) {

    $allowed = true;

   } else {

    $memberGroupsTable = $modx->getFullTableName('member_groups');
    $groupList = implode(',',$groups);

    $sql = "SELECT `id`
            FROM {$memberGroupsTable}
            WHERE `member` = '{$userId}'
            AND `user_group` IN ({$groupList});";
    $result = $modx->db->query($sql);

    if($modx->db->getRecordCount($result) > 0) {
     $allowed = true;
    }

   }
   
  }

  return $allowed;

 }

}

?>
