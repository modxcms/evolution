<?php namespace EvolutionCMS;

use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Interfaces\CoreInterface;

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

    public function __construct(CoreInterface $core, $theme = '')
    {
        $this->core = $core;

        if (empty($theme)) {
            $theme = $this->core->getConfig('manager_theme');
        }

        $this->theme = $theme;
    }

    public function makePartial($name)
    {
        return MODX_MANAGER_PATH . sprintf('media/style/%s/partials/%s.tpl', $this->theme, $name);
    }

    public function getPartial($name)
    {
        return file_get_contents($this->makePartial($name));
    }
}
