<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;

//'actions'=>array( 'edit'=>array(16,'edit_template'), 'duplicate'=>array(96,'new_template'), 'remove'=>array(21,'delete_template') ),
class Templates extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.templates';

    /**
     * @inheritdoc
     */
    public function getTabName(): string
    {
        return 'tabTemplates';
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
            'tabPageName' => $this->getTabName(),
            'tabName' => 'site_templates',
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory()
        ]);
    }

    protected function parameterOutCategory() : Collection
    {
        return Models\SiteTemplate::where('category', '=', 0)
            ->orderBy('templatename', 'ASC')
            ->get();
    }

    protected function parameterCategories() : Collection
    {
        return Models\Category::with('templates')
            ->whereHas('templates')
            ->orderBy('rank', 'ASC')
            ->get();
    }
}
