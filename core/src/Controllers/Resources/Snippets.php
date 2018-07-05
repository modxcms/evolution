<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;

//'actions'=>array('edit'=>array(22,'edit_snippet'), 'duplicate'=>array(98,'new_snippet'), 'remove'=>array(25,'delete_snippet')),
class Snippets extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.snippets';

    /**
     * @inheritdoc
     */
    public function getTabName(): string
    {
        return 'tabSnippets';
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasAnyPermissions([
            'new_snippet',
            'edit_snippet'
        ]);
    }

    public function getParameters(array $params = []) : array
    {
        return array_merge(parent::getParameters($params), [
            'tabName' => 'site_snippets',
            'items' => $this->parameterItems(),
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory()
        ]);
    }

    protected function parameterOutCategory() : Collection
    {
        return Models\SiteSnippet::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->get();
    }

    protected function parameterCategories() : Collection
    {
        return Models\Category::with('snippets')
            ->whereHas('snippets')
            ->orderBy('rank', 'ASC')
            ->get();
    }

    protected function parameterItems() : array
    {
        $out = [];

        $elements = Models\SiteSnippet::with('categories')
            ->orderBy('name', 'ASC')->get();
        /**
         * @var Models\SiteSnippet $element
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
