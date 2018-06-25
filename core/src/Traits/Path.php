<?php namespace EvolutionCMS\Traits;

trait Path
{
    /**
     * @return string
     */
    public function getCacheFolder()
    {
        return "assets/cache/";
    }

    /**
     * @param $key
     * @return string
     */
    public function getHashFile($key)
    {
        return $this->getCacheFolder() . "docid_" . $key . ".pageCache.php";
    }

    /**
     * Returns the manager relative URL/path with respect to the site root.
     *
     * @global string $base_url
     * @return string The complete URL to the manager folder
     */
    public function getManagerPath()
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
        return MODX_BASE_URL . $this->getCacheFolder();
    }
}
