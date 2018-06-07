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

        $this->loadSnippets();
    }

    public function loadSnippets()
    {
        $this->core->addSnippet('recentInfoList', $this->pathElement('snippet', 'welcome/RecentInfo'));
    }

    public function pathSnippet($name)
    {
        return MODX_MANAGER_PATH . sprintf('media/style/%s/chunks/%s.tpl', $this->theme, $name);
    }

    public function pathElement($type, $name)
    {
        return MODX_MANAGER_PATH . sprintf('media/style/%s/%s/%s.tpl', $this->theme, $type, $name);
    }

    public function getElement($type, $name)
    {
        return file_get_contents($this->pathElement($type, $name));
    }

    public function getSnippet($name)
    {
        return $this->getElement('snippets', $name);
    }

    public function getChunk($name)
    {
        return $this->getElement('chunks', $name);
    }
}
