<?php
/**
 * mutate_settings.ajax.php
 */
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

$action = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['action']);
$lang = preg_replace('/[^A-Za-z0-9_\s\+\-\.\/]/', '', $_POST['lang']);
$key = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['key']);
$value = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $_POST['value']);


$str = '';
$emptyCache = false;

switch (true) {
    case ($action == 'get' && preg_match('/^[A-z0-9_-]+$/',
            $lang) && file_exists(EVO_CORE_PATH . 'lang/' . $lang . '/global.php')): {
        include EVO_CORE_PATH . 'lang/' . $lang . '/global.php';
        $str = isset($key, $_lang, $_lang[$key]) ? $_lang[$key] : "";
        break;
    }
    case ($action == 'setsetting' && !empty($key) && !empty($value)): {
        \EvolutionCMS\Models\SystemSetting::query()->updateOrCreate(['setting_name' => $key], ['setting_value' => $value]);
        $str = "true";
        $emptyCache = true;
        break;
    }
    case ($action == 'updateplugin' && ($key == '_delete_' && !empty($lang))): {
        \EvolutionCMS\Models\SitePlugin::query()->where('name', $lang)->delete();
        $str = "true";
        $emptyCache = true;
        break;
    }
    case ($action == 'updateplugin' && (!empty($key) && !empty($lang) && !empty($value))): {
        \EvolutionCMS\Models\SitePlugin::query()->where('name', $lang)->update([$key => $value]);
        $str = "true";
        $emptyCache = true;
        break;
    }
    default: {
        break;
    }
}

if ($emptyCache) {
    $modx->clearCache('full');
}

echo $str;
