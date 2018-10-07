<?php namespace EvolutionCMS\Traits;

trait Settings
{
    /**
     * @var array
     */
    public $config = array();

    /**
     * @param $name
     * @param $value
     */
    public function setConfig($name, $value)
    {
        $this['config']
            ->set('cms.settings.' . $name, $value);
    }

    /**
     * @return array
     */
    public function allConfig()
    {
        return array_merge(
            $this->config,
            $this['config']->get('cms.settings', [])
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getConfig($name = '', $default = null)
    {
        return $this['config']
            ->get(
                'cms.settings.' . $name,
                get_by_key(
                    $this->config,
                    $name,
                    $default
                )
            );
    }

    /**
     * Get MODX settings including, but not limited to, the system_settings table
     */
    public function getSettings()
    {
        // setup default site id - new installation should generate a unique id for the site.
        if ($this->getConfig('site_id', '') === '') {
            $this->setConfig('site_id', 'MzGeQ2faT4Dw06+U49x3');
        }

        // store base_url and base_path inside config array
        //$this->config['base_url'] = MODX_BASE_URL;
        //$this->config['base_path'] = MODX_BASE_PATH;
        //$this->config['site_url'] = MODX_SITE_URL;
        $this->error_reporting = $this->getConfig('error_reporting');
        $this->setConfig(
            'filemanager_path',
            str_replace(
                '[(base_path)]',
                MODX_BASE_PATH,
                $this->getConfig('filemanager_path')
            )
        );
        $this->setConfig(
            'rb_base_dir',
            str_replace(
                '[(base_path)]',
                MODX_BASE_PATH,
                $this->getConfig('rb_base_dir')
            )
        );
    }
}
