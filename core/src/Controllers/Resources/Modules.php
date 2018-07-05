<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;

//'actions'=>array('edit'=>array(108,'edit_module'), 'duplicate'=>array(111,'new_module'), 'remove'=>array(110,'delete_module')),
class Modules extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.modules';

    /**
     * @inheritdoc
     */
    public function getTabName(): string
    {
        return 'tabModules';
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasAnyPermissions([
            'exec_module',
            'new_module',
            'edit_module',
            'save_module',
            'delete_module'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getParameters(array $params = []) : array
    {
        return array_merge(parent::getParameters($params), [
            'tabName' => 'site_modules',
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory(),
            'action'    => $this->parameterActionName()
        ]);
    }

    protected function parameterOutCategory() : Collection
    {
        return Models\SiteModule::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->get();
    }

    protected function parameterCategories() : Collection
    {
        return Models\Category::with('modules')
            ->whereHas('modules')
            ->orderBy('rank', 'ASC')
            ->get();
    }

    protected function parameterActionName() : string
    {
        switch(true) {
            case evolutionCMS()->hasPermission('edit_module'):
                $action = 'actions.edit';
                break;
            case evolutionCMS()->hasPermission('exec_module'):
                $action = 'actions.run';
                break;
            default:
                $action = '';
        }
        return $action;
    }
}
