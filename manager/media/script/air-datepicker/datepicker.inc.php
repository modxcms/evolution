<?php
class DATEPICKER {
    function __construct() {
    }
    function getDP() {
        global $modx;
        
        $load_script = file_get_contents(MODX_MANAGER_PATH . 'media/script/air-datepicker/datepicker.tpl');
        if(!isset($modx->config['lang_code'])) $modx->config['lang_code'] = $this->getLangCode();
        return $modx->mergeSettingsContent($load_script);
    }
    function getLangCode() {
        global $modx, $modx_lang_attribute;
        
        if(!$modx_lang_attribute) return 'en';
        
        $lc = $modx_lang_attribute;
        if($lc=='uk') return 'ru';
        $dp_path = str_replace('\\','/',dirname(__FILE__));
        if(is_file("{$dp_path}/i18n/datepicker.{$lc}.js")) return $modx_lang_attribute;
        else                                               return 'en';
    }
}
