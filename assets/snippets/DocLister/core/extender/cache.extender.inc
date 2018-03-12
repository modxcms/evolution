<?php

use \Doctrine\Common\Cache\Cache;

/**
 * Class cache_DL_Extender
 */
class cache_DL_Extender extends extDocLister
{
    private $keyGenerator = null;
    private $key = '';
    private $lifetime = 0;
    private $enabled = false;

    /**
     * Запуск экстендера.
     * Метод определяющий действия экстендера при инициализации
     */
    protected function run()
    {
        $result = (bool)$this->getCFGDef('cache', 1) && isset($this->modx->cache) && ($this->modx->cache instanceof Cache);
        $this->enabled = $result;
        if ($result) {
            $this->setCacheLifetime((int)$this->getCFGDef('cacheLifetime', 0));
            $this->setCacheKey();
        }
        return $result;
    }

    /**
     * @param mixed $data
     * @param string $postfix
     * @param int $lifetime
     * @return bool
     */
    public function save($data, $postfix = '')
    {
        $out = false;
        if ($this->enabled) {
            $key = $this->getCacheKey() . '_' . $postfix;
            $lifetime = $this->getCacheLifetime();
            $out = $this->modx->cache->save($key, $data, $lifetime);
        }

        return $out;
    }

    /**
     * @param string $postfix
     * @return string
     */
    public function load($postfix = '')
    {
        $out = false;
        if ($this->enabled) {
            $key = $this->getCacheKey() . '_' . $postfix;
            $out = $this->modx->cache->fetch($key);
        }

        return $out;
    }

    /**
     * @param callable $function
     * @return cache_DL_Extender
     */
    public function setCacheKeyGenerator($function = null)
    {
        if ($this->enabled && is_callable($function)) {
            $this->keyGenerator = $function;
        }

        return $this;
    }

    /**
     * @param int $time
     * @return $this
     */
    public function setCacheLifetime($time = 0)
    {
        $time = (int)$time;
        $this->lifetime = $time;

        return $this;
    }

    /**
     * @return string
     */
    public function setCacheKey()
    {
        if ($this->enabled && is_callable($this->keyGenerator)) {
            $key = call_user_func_array($this->keyGenerator, array(
                'modx'      => $this->modx,
                'DocLister' => $this
            ));
        } else {
            if (!$key = $this->getCFGDef('cacheKey', '')) {
                $config = $this->DocLister->config->getConfig();
                $key = md5(json_encode($config));
            }
            $strategy = $this->getCFGDef('cacheStrategy', '');
            switch ($strategy) {
                case 'user':
                    $uid = $this->modx->getLoginUserID('web');
                    $key .= $uid ? '_webuser' : '';
                    break;
                case 'usergroup':
                    $uid = $this->modx->getLoginUserID('web');
                    if ($uid && !empty($_SESSION['webUserGroupNames'])) {
                        $key .= '_webgroups_' . md5(implode(',', $_SESSION['webUserGroupNames']));
                    }
                    break;
            }
        }

        $this->key = $key ? $key : '';

        return $this;
    }

    /**
     * @return string
     */
    private function getCacheKey()
    {
        return $this->key;
    }

    /**
     * @return int
     */
    private function getCacheLifetime()
    {
        return $this->lifetime;
    }

}
