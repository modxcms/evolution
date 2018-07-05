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
            'new_module',
            'edit_module'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getParameters(array $params = []) : array
    {
        return array_merge(parent::getParameters($params), [
            'tabName' => 'site_modules',
            'items' => $this->parameterItems(),
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory()
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

    protected function parameterItems() : array
    {
        $out = [];

        $elements = Models\SiteModule::with('categories')
            ->orderBy('name', 'ASC')->get();
        /**
         * @var Models\SiteModule $element
         */
        foreach ($elements as $element) {
            $out[] = [
                'disabled' => $element->disabled,
                'name' => $element->name,
                'id' => $element->getKey(),
                'description' => $element->description,
                'locked' => $element->locked,
                'category' => $element->categoryName($this->managerTheme->getLexicon('no_category')),
                'catid' => $element->categoryId()
            ];
        }
        return $out;
    }
}
