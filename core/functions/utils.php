<?php

if (!function_exists('evo_guest')) {
    function evo_guest($content)
    {
        if(!EvolutionCMS()->getLoginUserID()){
            $content = '';
        }
        return $content;
    }
}

if (!function_exists('evo_auth')) {
    function evo_auth($content)
    {
        if (!EvolutionCMS()->getLoginUserID()) {
            $content = '';
        }
        return $content;
    }
}

if (!function_exists('var_debug')) {
    /**
     * Dumps information about a variable in Tracy Debug Bar.
     * @tracySkipLocation
     * @param mixed $var
     * @param string $title
     * @param array $options
     * @return mixed  variable itself
     */
    function var_debug($var, $title = null, array $options = null)
    {
        return EvolutionCMS\Tracy\Debugger::barDump($var, $title, $options);
    }
}

if (!function_exists('evo_parser')) {
    function evo_parser($content)
    {
        $core = evolutionCMS();
        $core->minParserPasses = 2;
        $core->maxParserPasses = 10;

        $out = \EvolutionCMS\Parser::getInstance($core)->parseDocumentSource($content, $core);

        $core->minParserPasses = -1;
        $core->maxParserPasses = -1;

        return $out;
    }
}

if (!function_exists('evo_raw_config_settings')) {
    function evo_raw_config_settings(): array
    {
        $configFile = config_path('cms/settings.php', !app()->isProduction());

        /** @var Illuminate\Filesystem\Filesystem $files */
        $files = app('files');

        if ($files->isFile($configFile)) {
            $config = $files->getRequire($configFile);
        }

        return isset($config) && is_array($config) ? $config : [];
    }
}

if (!function_exists('evo_save_config_settings')) {
    function evo_save_config_settings(array $config = []): bool
    {
        /** @var Illuminate\Filesystem\Filesystem $files */
        $files = app('files');

        $data = $files->put(
            config_path('cms/settings.php', !app()->isProduction()),
            '<?php return ' . var_export($config, true) . ';'
        );

        return is_bool($data) ? $data : true;
    }
}

if (!function_exists('evo_update_config_settings')) {
    function evo_update_config_settings(string $key, $data = null): bool
    {
        $config = evo_raw_config_settings();
        $config[$key] = $data;
        return evo_save_config_settings($config);
    }
}

if (!function_exists('evo_delete_config_settings')) {
    function evo_delete_config_settings(string $key)
    {
        $config = evo_raw_config_settings();
        unset($config[$key]);
        return evo_save_config_settings($config);
    }
}
