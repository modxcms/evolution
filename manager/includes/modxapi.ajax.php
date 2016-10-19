<?php
/**
 * modxapi.ajax.php
 * 
 */
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

require_once(dirname(__FILE__) . '/protect.inc.php');

$api    = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_REQUEST['api']);
$action = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_REQUEST['action']);
$key    = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_REQUEST['key']);
$value  = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_REQUEST['value']);
$lang   = preg_replace('/[^A-Za-z0-9_\s\+\-\.\/]/', '', $_REQUEST['lang']);

$api = $modx->db->escape($api);
$action = $modx->db->escape($action);
$key = $modx->db->escape($key);
$value = $modx->db->escape($value);
$lang = $modx->db->escape($lang);

$str = '';

$error = 'Saving not successful, check your system events-log. Important: Set "Allow root / udperms_allowroot" in configuration to on.';
$success = false;

switch($api) {
	case 'resource':
		include_once(MODX_BASE_PATH . "assets/lib/MODxAPI/modResource.php");
		$value = intval($value);
		$modx->doc = new modResource($modx);
		$modx->doc->edit($value);
		switch($action) {
			case 'publish':
				if (!$modx->hasPermission('edit_document')) { $error = 'No permission'; break; };  
				$published = $modx->doc->get('published');
				$str = $published == 0 ? 1 : 0;
				if($value == $site_start && $str == 0) {
					$error = 'Resource "site_start" cannot be unpublished';
				} else {
					// Assure modResource does not rely on pub_date to re-publish a resource 
					if($str == 0 && $modx->doc->get('pub_date') < time() + $server_offset_time) {
						$modx->doc->set('pub_date', 0);
						$modx->doc->set('publishedon', 0);
					}
					$modx->doc->set('published', $str);
					$success = $modx->doc->save(true, true);
				}
				break;
			case 'delete':
				if (!$modx->hasPermission('delete_document')) { $error = 'No permission'; break; };
				$deleted = $modx->doc->get('deleted');
				$str = $deleted == 0 ? 1 : 0;
				if($value == $site_start && $str == 1) {
					$error = 'Resource "site_start" cannot be deleted';
				} else {
					$modx->doc->set('deleted', $str);
					$success = $modx->doc->save(true, true);
				}
				break;
		}
}

echo $success !== false ? $str : $error;