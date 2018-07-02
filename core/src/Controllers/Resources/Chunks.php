<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;

//'actions'=>array('edit'=>array(78,'edit_chunk'), 'duplicate'=>array(97,'new_chunk'), 'remove'=>array(80,'delete_chunk')),
class Chunks extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.tab.chunks';

    /**
     * @inheritdoc
     */
    public function getTabName(): string
    {
        return 'tabChunks';
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        return evolutionCMS()->hasAnyPermissions([
            'new_chunk',
            'edit_chunk'
        ]);
    }

    /**
     * @inheritdoc
     */
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
        return Models\SiteHtmlsnippet::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->get();
    }

    protected function parameterCategories() : Collection
    {
        return Models\Category::with('chunks')
            ->whereHas('chunks')
            ->orderBy('rank', 'ASC')
            ->get();
    }

    protected function parameterItems() : array
    {
        $out = [];

        $elements = Models\SiteHtmlsnippet::with('categories')
            ->orderBy('name', 'ASC')->get();
        /**
         * @var Models\SiteHtmlsnippet $element
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
