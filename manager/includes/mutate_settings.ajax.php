<?php
/**
 * mutate_settings.ajax.php
 * 
 */
require_once(dirname(__FILE__) . '/protect.inc.php');

$action = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['action']);
$lang = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['lang']);
$key = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['key']);
$value = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['value']);

$str = '';
if($action == 'get') {
    $langfile = dirname(__FILE__) . '/lang/'.$lang.'.inc.php';
    if(file_exists($langfile)) {
        $str = getLangStringFromFile($langfile, $key);
    }
} elseif($action == 'setsetting') {
	if(!empty($key) && !empty($value)) {
		$sql = "UPDATE ".$modx->getFullTableName("system_settings")." SET setting_value='{$value}' WHERE setting_name = '{$key}';";
		$str = "true";
		if(!@$rs = $modx->db->query($sql)) {
			$str = "false";
		}
	}
}

echo $str;

function getLangStringFromFile($file, $key) {
    include($file);
    return $_lang[$key];
}