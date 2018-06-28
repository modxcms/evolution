<?php namespace EvolutionCMS;

use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Interfaces\CoreInterface;
use Exception;
use View;

class ManagerTheme implements ManagerThemeInterface
{
    /**
     * @var CoreInterface
     */
    protected $core;

    /**
     * @var $theme
     */
    protected $theme;

    protected $namespace = 'manager';

    protected $lang = 'en';

    protected $actions = [
        /** frame management - show the requested frame */
        1,
        /** show the homepage */
        2,
        /** document data */
        3,
        /** content management */
        85, 27, 4, 5, 6, 63, 51, 52, 61, 62, 56,
        /** show the wait page - gives the tree time to refresh (hopefully) */
        7,
        /** let the user log out */
        8,
        /** user management */
        87, 88, 89, 90, 11, 12, 32, 28, 34, 33,
        /** role management */
        38, 35, 36, 37,
        /** category management */
        120, 121,
        /** template management */
        16, 19, 20, 21, 96, 117,
        /** snippet management */
        22, 23, 24, 25, 98,
        /** htmlsnippet management */
        78, 77, 79, 80, 97,
        /** show the credits page */
        18,
        /** empty cache & synchronisation */
        26,
        /** Module management */
        106, 107, 108, 109, 110, 111, 112, 113,
        /** plugin management */
        100, 101, 102, 103, 104, 105, 119,
        /** view phpinfo */
        200,
        /** errorpage */
        29,
        /** file manager */
        31,
        /** access permissions */
        40, 91,
        /** access groups processor */
        41, 92,
        /** settings editor */
        17, 118,
        /** save settings */
        30,
        /** system information */
        53,
        /** optimise table */
        54,
        /** view logging */
        13,
        /** empty logs */
        55,
        /** calls test page    */
        999,
        /** Empty recycle bin */
        64,
        /** Messages */
        10,
        /** Delete a message */
        65,
        /** Send a message */
        66,
        /** Remove locks */
        67,
        /** Site schedule */
        70,
        /** Search */
        71,
        /** About */
        59,
        /** Add weblink */
        72,
        /** User management */
        75, 99, 86,
        /** template/ snippet management */
        76,
        /** Export to file */
        83,
        /** Resource Selector  */
        84,
        /** Backup Manager */
        93,
        /** Duplicate Document */
        94,
        /** Import Document from file */
        95,
        /** Help */
        9,
        /** Template Variables - Based on Apodigm's Docvars */
        300, 301, 302, 303, 304, 305,
        /** Event viewer: show event message log */
        114, 115, 116, 501
    ];

    public function __construct(CoreInterface $core, $theme = '')
    {
        $this->core = $core;

        if (empty($theme)) {
            $theme = $this->core->getConfig('manager_theme');
        }

        $this->theme = $theme;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function view($name, array $params = [])
    {
        return View::make(
            $this->namespace . '::' . $name,
            $this->getViewAttributes($params)
        );
    }

    public function getViewAttributes(array $params = [])
    {
        $baseParams = [
            'modx' => $this->core,
            'modx_lang_attribute' => $this->lang,
            'manager_theme' => $this->theme
        ];

        return array_merge($baseParams, $params);
    }

    public function handle ($action) {
        $controller = $this->makeControllerPath($action);

        if (! \in_array($action, $this->actions, true) || ! file_exists($controller)) {
            $controller = null;
        }

        return $controller;
    }

    protected function makeControllerPath($action) {
        return MODX_MANAGER_PATH . 'controllers/' . $action . '.php';
    }
}
