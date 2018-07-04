<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;

class Resources extends AbstractResources implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.resources';

    protected $tabs = [
        Resources\Templates::class,
        Resources\Tv::class,
        Resources\Chunks::class,
        Resources\Snippets::class,
        Resources\Plugins::class,
        Resources\Modules::class
    ];

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return true;
    }

    public function getParameters(array $params = []) : array
    {
        $tabs = [];
        foreach ($this->tabs as $tabClass) {
            if (class_exists($tabClass) &&
                \in_array(ManagerTheme\TabControllerInterface::class, class_implements($tabClass), true)
            ) {
                $tabController = new $tabClass($this->managerTheme);
                if ($tabController->canView()) {
                    $tabs[$tabController->getTabName()] = $tabController;
                }
            }
        }

        $activeTab = '';
        $_ = array_values($tabs);
        if (isset($_GET['tab']) && is_numeric($_GET['tab']) && isset($_[$_GET['tab']])) {
            $activeTab = $_GET['tab'];
        }

        return array_merge(compact('tabs'), parent::getParameters($params), ['activeTab' => $activeTab]);
    }
}
