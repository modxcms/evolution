<?php namespace EvolutionCMS\Controllers\Resources;

use EvolutionCMS\ManagerTheme;
use EvolutionCMS\Models;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent;

//'actions'=>array('edit'=>array(301,'edit_template'), 'duplicate'=>array(304,'edit_template'), 'remove'=>array(303,'edit_template')),
class Tv extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.resources.tv';

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

    public function getParameters(array $params = []): array
    {
        return array_merge(parent::getParameters($params), [
            'tabPageName' => $this->getTabName(),
            'tabName' => 'site_tmplvars',
            'categories' => $this->parameterCategories(),
            'outCategory' => $this->parameterOutCategory()
        ]);
    }

    protected function parameterOutCategory(): Collection
    {
        return Models\SiteTmplvar::where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->lockedView()
            ->get();
    }

    protected function parameterCategories(): Collection
    {
        return Models\Category::with('tvs')
            ->whereHas('tvs', function (Eloquent\Builder $builder) {
                return $builder->lockedView();
            })->orderBy('rank', 'ASC')
            ->get();
    }
}
