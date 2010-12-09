<?php
/**
 * mutate_settings.ajax.php
 * 
 */
require_once(dirname(__FILE__) . '/protect.inc.php');

$action = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['action']);
$lang = preg_replace('/[^A-Za-z0-9_\s\+\-\.\/]/', '', $_POST['lang']);
$key = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['key']);
$value = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['value']);

$action = $modx->db->escape($action);
$lang = $modx->db->escape($lang);
$key = $modx->db->escape($key);
$value = $modx->db->escape($value);

$str = '';
$emptyCache = false;

if($action == 'get') {
    $langfile = dirname(__FILE__) . '/lang/'.$lang.'.inc.php';
    if(file_exists($langfile)) {
        $str = getLangStringFromFile($langfile, $key);
    }
} elseif($action == 'setsetting') {
    if(!empty($key) && !empty($value)) {
        $sql = "REPLACE INTO ".$modx->getFullTableName("system_settings")." (setting_name, setting_value) VALUES('{$key}', '{$value}');";
        $str = "true";
        if(!@$rs = $modx->db->query($sql)) {
            $str = "false";
        } else {
            $emptyCache = true;
        }
    }
} elseif($action == 'updateplugin') {

    if($key == '_delete_' && !empty($lang)) {
        $sql = "DELETE FROM " . $modx->getFullTableName("site_plugins") . " WHERE name='{$lang}'";
        $str = "true";
        if(!@$rs = $modx->db->query($sql)) {
            $str = "false";
        } else {
            $emptyCache = true;
        }
    } elseif(!empty($key) && !empty($lang) && !empty($value)) {
        $sql = "UPDATE ".$modx->getFullTableName("site_plugins")." SET {$key}='{$value}' WHERE name = '{$lang}';";
        $str = "true";
        if(!@$rs = $modx->db->query($sql)) {
            $str = "false";
        } else {
            $emptyCache = true;
        }
    }
}

if($emptyCache) {
    include_once dirname(dirname(__FILE__)) . "/processors/cache_sync.class.processor.php";
    $sync = new synccache();
    $sync->setCachepath("../assets/cache/");
    $sync->setReport(false);
    $sync->emptyCache();
}

echo $str;

function getLangStringFromFile($file, $key) {
    include($file);
    return $_lang[$key];
}