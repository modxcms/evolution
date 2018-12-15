<?php namespace EvolutionCMS\Traits;

trait Path
{
    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The custom database path defined by the developer.
     *
     * @var string
     */
    protected $databasePath;

    /**
     * Get the path to the core directory.
     *
     * @param  string  $path Optionally, a path to append to the core path
     * @return string
     */
    public function path($path = '')
    {
        return rtrim(EVO_CORE_PATH, '/') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * {@inheritdoc}
     */
    public function basePath()
    {
        return EVO_CORE_PATH;
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->path('lang-laravel');
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->path('config');
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return MODX_BASE_PATH;
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return $this->storagePath ?: EVO_STORAGE_PATH;
    }

    /**
     * Set the storage directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useStoragePath($path)
    {
        $this->storagePath = $path;
        $this->instance('path.storage', $path);
        return $this;
    }

    /**
     * Get the path to the database directory.
     *
     * @param  string  $path Optionally, a path to append to the database path
     * @return string
     */
    public function databasePath($path = '')
    {
        return $this->databasePath ?: $this->path('database' . ($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    /**
     * Set the database directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useDatabasePath($path)
    {
        $this->databasePath = $path;

        $this->instance('path.database', $path);

        return $this;
    }

    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     * @return string
     */
    public function resourcePath($path = '')
    {
        return $this->publicPath() . DIRECTORY_SEPARATOR . 'assets' . ($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @param  string  $path Optionally, a path to append to the bootstrap path
     * @return string
     */
    public function bootstrapPath($path = '')
    {
        return $this->storagePath() . 'bootstrap' . ($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * @return string
     */
    public function getCacheFolder()
    {
        return 'cache/';
    }

    /**
     * @param $key
     * @return string
     */
    public function getHashFile($key)
    {
        return $this->bootstrapPath('docid_' . $key . '.pageCache.php');
    }

    /**
     * @deprecated
     */
    public function getManagerPath()
    {
        return $this->getManagerUrl();
    }

    /**
     * Returns the manager relative URL/path with respect to the site root.
     *
     * @global string $base_url
     * @return string The complete URL to the manager folder
     */
    public function getManagerUrl()
    {
        return MODX_MANAGER_URL;
    }

    /**
     * Returns the cache relative URL/path with respect to the site root.
     *
     * @global string $base_url
     * @return string The complete URL to the cache folder
     */
    public function getCachePath()
    {
        return EVO_STORAGE_PATH . $this->getCacheFolder();
    }

    /**
     * @return string
     * @deprecated
     */
    public function getSiteCachePath()
    {
        return $this->bootstrapPath();
    }

    public function getSiteCacheFilePath()
    {
        return $this->bootstrapPath('siteCache.idx.php');
    }

    public function getSitePublishingFilePath()
    {
        return $this->bootstrapPath('sitePublishing.idx.php');
    }
}
