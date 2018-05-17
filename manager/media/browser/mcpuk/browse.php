<?php

/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 2.54
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

require "core/autoload.php"; // Init MODX

function returnNoPermissionsMessage($role) {
	global $_lang;
	echo sprintf($_lang['files_management_no_permission'], $role);
	exit;
}

if( $_GET['type'] == 'images' && !$modx->hasPermission('file_manager') && !$modx->hasPermission('assets_images')) returnNoPermissionsMessage('assets_images');
if( $_GET['type'] == 'files'  && !$modx->hasPermission('file_manager') && !$modx->hasPermission('assets_files'))  returnNoPermissionsMessage('assets_files');

$browser = new browser($modx);
$browser->action();
