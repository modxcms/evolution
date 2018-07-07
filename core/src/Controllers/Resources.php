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
        $activeTab = $this->needTab();
        foreach ($this->tabs as $tabN => $tabClass) {
            if (($tabController = $this->makeTab($tabClass)) !== null) {
                if ($activeTab !== (string)$tabN) {
                    $tabController->setNoData();
                }
                $tabs[$tabController->getTabName()] = $tabController;
            }
        }

        return array_merge(compact('tabs'), parent::getParameters($params), ['activeTab' => (string)$activeTab]);
    }

    protected function makeTab($tabClass) :? ManagerTheme\TabControllerInterface
    {
        $tabController = null;
        if (class_exists($tabClass) &&
            is_a($tabClass, ManagerTheme\TabControllerInterface::class, true)
        ) {
            $tabController = new $tabClass($this->managerTheme);
            if (! $tabController->canView()) {
                $tabController = null;
            }
        }

        return $tabController;
    }

    /**
     * @inheritdoc
     */
    public function render(array $params = []) : string
    {
        if (is_ajax() && ($tab = $this->needTab()) !== null) {
            return (isset($this->tabs[$tab]) && ($tabController = $this->makeTab($this->tabs[$tab])) !== null) ?
                $tabController->render(
                    $tabController->getParameters()
                ) : '';
        }
        return parent::render($params);
    }

    protected function needTab()
    {
        return get_by_key($_GET, 'tab', null, function ($val) {
            return is_numeric($val) && array_key_exists($val, $this->tabs);
        });
    }
}
