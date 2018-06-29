<?php
class DATEPICKER {
    /**
     * @return string
     */
    public function getDP() {
        $modx = evolutionCMS();

        $load_script = file_get_contents(__DIR__.'/datepicker.tpl');
        if(!isset($modx->config['lang_code'])) $modx->config['lang_code'] = $this->getLangCode();
		$modx->config['datetime_format_lc'] = isset($modx->config['datetime_format']) ? strtolower($modx->config['datetime_format']) : 'dd-mm-yyyy';
        return $modx->mergeSettingsContent($load_script);
    }

    /**
     * @return string
     */
    public function getLangCode() {
        $lang = evolutionCMS()->get('ManagerTheme')->getLang();

        if ($lang === 'uk') {
            $lang = 'ru';
        }

        $dp_path = str_replace('\\', '/', __DIR__);

        return is_file("{$dp_path}/i18n/datepicker.{$lang}.js") ? $lang : 'en';
    }
}
