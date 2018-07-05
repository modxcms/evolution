<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;

//'actions'=>array('edit'=>array(102,'edit_plugin'), 'duplicate'=>array(105,'new_plugin'), 'remove'=>array(104,'delete_plugin')),
class Plugins extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.plugins';

    /**
     * @inheritdoc
     */
    public function getTabName(): string
    {
        return 'tabPlugins';
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasAnyPermissions([
            'new_plugin',
            'edit_plugin'
        ]);
    }

    public function getParameters(array $params = []): array
    {
        return array_merge(parent::getParameters($params), [
            'tabPageName' => $this->getTabName(),
            'tabName' => 'site_plugins',
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory(),
            'checkOldPlugins' => $this->checkOldPlugins()
        ]);
    }

    protected function parameterOutCategory(): Collection
    {
        return Models\SitePlugin::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->get();
    }

    protected function parameterCategories(): Collection
    {
        return Models\Category::with('plugins')
            ->whereHas('plugins')
            ->orderBy('rank', 'ASC')
            ->get();
    }

    // :TODO check old plugins
    protected function checkOldPlugins(): bool
    {
        return true;
    }
}
