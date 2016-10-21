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

$error = 'Unknown error';
$ajaxResponse = true; // For processors & $modx->webAlertAndQuit()

switch($api) {
	case 'resource':
		include_once(MODX_BASE_PATH . "assets/lib/MODxAPI/modResource.php");
		$value = intval($value);
		$modx->doc = new modResource($modx);
		$modx->doc->edit($value);
		switch($action) {
			case 'publish':
				$published = $modx->doc->get('published');
				$str = $published == 0 ? 1 : 0;
				if($str == 1) {
					$_REQUEST['id'] = $value;
					require_once(dirname(__FILE__) . '/../processors/publish_content.processor.php');
				} else {
					$_REQUEST['id'] = $value;
					require_once(dirname(__FILE__) . '/../processors/unpublish_content.processor.php');
				}
				break;
			case 'delete':
				$deleted = $modx->doc->get('deleted');
				$str = $deleted == 0 ? 1 : 0;
				if($str == 1) {
					$_GET['id'] = $value; 
					require_once(dirname(__FILE__) . '/../processors/delete_content.processor.php');
				} else {
					$_REQUEST['id'] = $value;
					require_once(dirname(__FILE__) . '/../processors/undelete_content.processor.php');
				}
				break;
		}
}

echo isset($response) ? $response : $error;