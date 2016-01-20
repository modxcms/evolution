<?php

require_once (MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');

class AssetsHelper
{
    protected $modx = null;
    protected $fs = null;

    /**
     * @var cached reference to singleton instance
     */
    protected static $instance;

    /**
     * gets the instance via lazy initialization (created on first usage)
     *
     * @return self
     */
    public static function getInstance(DocumentParser $modx)
    {

        if (null === self::$instance) {
            self::$instance = new self($modx);
        }
        return self::$instance;
    }

    /**
     * is not allowed to call from outside: private!
     *
     */
    private function __construct(DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->fs = \Helpers\FS::getInstance();
    }

    /**
     * prevent the instance from being cloned
     *
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * prevent from being unserialized
     *
     * @return void
     */
    private function __wakeup()
    {

    }

        /**
     * @param $name
     * @param array $params
     * @return string
     */

    public function registerScript($name, $params) {
        $out = '';
        if (!isset($this->modx->loadedjscripts[$name])) {
            $src = $params['src'];
            $remote = strpos($src, "http") !== false;
            if (!$remote) {
                $src = $this->modx->config['site_url'].$src;
                if (!$this->fs->checkFile($params['src'])) {
                    $this->modx->logEvent(0, 3, 'Cannot load '.$src, 'Assets helper');
                    return false;    
                }
            }

            $type = isset($params['type']) ? $params['type'] : end(explode('.',$src));
            if ($type == 'js') {
                $out = '<script type="text/javascript" src="' . $src . '"></script>';
            } else {
                $out = '<link rel="stylesheet" type="text/css" href="'. $src .'">';
            }

            $this->modx->loadedjscripts[$name] = $params;

        } else {
            $out = false;
        }
        return $out;
    }
}