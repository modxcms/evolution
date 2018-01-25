<?php
class DATEPICKER {
    function __construct() {
    }
    function getDP() {
        global $modx;
        
        $load_script = file_get_contents(dirname(__FILE__).'/datepicker.tpl');
        if(!isset($modx->config['lang_code'])) $modx->config['lang_code'] = $this->getLangCode();
		$modx->config['datetime_format_lc'] = isset($modx->config['datetime_format']) ? strtolower($modx->config['datetime_format']) : 'dd-mm-yyyy';
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
