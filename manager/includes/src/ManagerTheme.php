<?php namespace EvolutionCMS;

use EvolutionCMS\Interfaces\ManagerThemeInterface;
use EvolutionCMS\Interfaces\CoreInterface;
use Exception;

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

        $this->loadChunks();
    }

    public function loadSnippets()
    {
        $this->addSnippet('recentInfoList', $this->getSnippet('welcome/RecentInfo'));
    }

    public function loadChunks()
    {
        $this->addChunk('welcome/RecentInfo', $this->getChunk('welcome/RecentInfo'));
        $this->addChunk('welcome/StartUpScript', $this->getChunk('welcome/StartUpScript'));
        $this->addChunk('welcome/Widget', $this->getChunk('welcome/Widget'));
        $this->addChunk('welcome/WrapIcon', $this->getChunk('welcome/WrapIcon'));
    }

    protected function pathElement($type, $name, $ext)
    {
        return MODX_MANAGER_PATH . sprintf('media/style/%s/%s/%s.%s', $this->theme, $type, $name, $ext);
    }

    public function getElement($type, $name)
    {
        switch ($type) {
            case 'chunk':
                return file_get_contents($this->pathElement($type, $name, 'tpl'));
                break;
            case 'snippet':
                return file_get_contents($this->pathElement($type, $name, 'php'));
                break;
            default:
                throw new Exception;
        }
    }

    public function getSnippet($name)
    {
        return $this->getElement('snippets', $name);
    }

    public function getChunk($name)
    {
        return $this->getElement('chunks', $name);
    }

    public function addElement($name, $code, $type)
    {
        switch ($type) {
            case 'chunk':
                $this->addChunk($name, $code);
                break;
            case 'snippet':
                $this->addSnippet($name, $code);
                break;
            default:
                throw new Exception;
        }
    }

    public function addSnippet($name, $code)
    {
        $this->core->addSnippet(
            $name,
            $code,
            'manager',
            array(
                'managerTheme' => $this
            )
        );
    }

    public function addChunk($name, $code)
    {
        $this->core->addChunk($name, $code, 'manager');
    }
}
