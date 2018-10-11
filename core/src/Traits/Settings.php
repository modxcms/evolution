<?php namespace EvolutionCMS\Traits;

trait Settings
{
    /**
     * @var array
     */
    public $config = [];

    protected $casts = [
        'error_reporting' => 'int',
        'site_start' => 'int',
        'error_page' => 'int',
        'cache_type' => 'int',
        'unauthorized_page' => 'int',
        'server_offset_time' => 'int',
        'site_unavailable_page' => 'int',
        'site_status' => 'bool',
        'use_alias_path' => 'bool',
        'seostrict' => 'bool',
        'make_folders' => 'bool',
        'friendly_urls' => 'bool',
        'xhtml_urls' => 'bool',
        'aliaslistingfolder' => 'bool',
        'friendly_alias_urls' => 'bool',
        'enable_cache' => 'bool',
        'enable_at_syntax' => 'bool',
        'enable_filter' => 'bool',
    ];

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
        $config = get_by_key(
            $this->config,
            $name,
            ''
        );

        if ($config === '') {
            $config = $default;
        }

        $value = $this['config']->get(
            'cms.settings.' . $name,
            $config
        );

        return $this->castAttribute($name, $value);
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

    /**
     * Cast an attribute to a native PHP type.
     *
     * @see \Illuminate\Database\Eloquent\Concerns\HasAttributes::castAttribute
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($value === null) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            default:
                return $value;
        }
    }

    protected function getCastType($key)
    {
        return isset($this->casts[$key]) ? trim(strtolower($this->casts[$key])) : null;
    }
}
