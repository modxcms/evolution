<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;

//'actions'=>array('edit'=>array(301,'edit_template'), 'duplicate'=>array(304,'edit_template'), 'remove'=>array(303,'edit_template')),
class Tv extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.tab.tv';

    /**
     * @inheritdoc
     */
    public function getTabName(): string
    {
        return 'tabVariables';
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasAnyPermissions([
            'new_template',
            'edit_template'
        ]);
    }

    public function getParameters(array $params = []) : array
    {
        return array_merge(parent::getParameters($params), [
            'items' => $this->parameterItems(),
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory()
        ]);
    }

    protected function parameterOutCategory() : Collection
    {
        return Models\SiteTmplvar::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->get();
    }

    protected function parameterCategories() : Collection
    {
        return Models\Category::with('tvs')
            ->whereHas('tvs')
            ->orderBy('rank', 'ASC')
            ->get();
    }

    protected function parameterItems() : array
    {
        $out = [];

        $elements = Models\SiteTmplvar::with('categories')
            ->orderBy('name', 'ASC')->get();
        /**
         * @var Models\SiteTmplvar $element
         */
        foreach ($elements as $element) {
            $out[] = [
                'reltpl' => $element->templates()->count(),
                'caption' => $element->caption,
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
