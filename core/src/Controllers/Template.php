<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent;

class Template extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.template';

    protected $events = [
        'OnTempFormPrerender',
        'OnTempFormRender'
    ];

    /** @var Models\SiteTemplate|null */
    private $object;

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(16, $this->getElementId())
            ->first();
        if ($out !== null) {
            return sprintf($this->managerTheme->getLexicon('error_no_privileges'), $out->username);
        }

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        if($this->getIndex() == 16) {
            return $this->managerTheme->getCore()->hasPermission('edit_template');
        }
        if($this->getIndex() == 19) {
            return $this->managerTheme->getCore()->hasPermission('new_template');
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function process() : bool
    {
        $this->object = $this->parameterData();
        $this->parameters = [
            'data' => $this->object,
            'categories'       => $this->parameterCategories(),
            'tvSelected'       => $this->parameterTvSelected(),
            'categoriesWithTv' => $this->parameterCategoriesWithTv(
                $this->object->tvs->reject(
                    function (Models\SiteTmplvar $item) {
                        return $item->category === 0;
                    })->pluck('id')->toArray()
            ),
            'tvOutCategory'    => $this->parameterTvOutCategory(
                $this->object->tvs->reject(
                    function (Models\SiteTmplvar $item) {
                        return $item->category !== 0;
                    })->pluck('id')->toArray()
            ),
            'action'           => $this->getIndex(),
            'events'           => $this->parameterEvents(),
            'actionButtons'    => $this->parameterActionButtons()
        ];

        return true;
    }

    /**
     * @return Models\SiteTemplate
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SiteTemplate $data */
        $data = Models\SiteTemplate::with('tvs')
            ->firstOrNew(['id'   => $id], [
                    'category'   => (int)get_by_key($_REQUEST, 'catid', 0),
                    'selectable' => 1
                ]);

        if ($id > 0) {
            if (!$data->exists) {
                $this->managerTheme->alertAndQuit('No database record has been found for this template.');
            }

            $_SESSION['itemname'] = $data->templatename;
            if ($data->locked == 1 && $_SESSION['mgrRole'] != 1) {
                $this->managerTheme->alertAndQuit('error_no_privileges');
            }
        } else {
            $_SESSION['itemname'] = $this->managerTheme->getLexicon("new_template");
        }

        $values = $this->managerTheme->loadValuesFromSession($_POST);

        if ($values) {
            $data->fill($values);
        }

        return $data;
    }

    protected function parameterCategories(): Collection
    {
        return Models\Category::orderBy('rank', 'ASC')
            ->orderBy('category', 'ASC')
            ->get();
    }

    protected function parameterTvSelected()
    {
        return array_unique(array_map('intval', get_by_key($_POST, 'assignedTv', [], 'is_array')));
    }

    protected function parameterTvOutCategory(array $ignore = []): Collection
    {
        $query = Models\SiteTmplvar::with('templates')
            ->where('category', '=', 0)
            ->orderBy('name', 'ASC');

        if (!empty($ignore)) {
            $query = $query->whereNotIn('id', $ignore);
        }

        return $query->get();
    }

    protected function parameterCategoriesWithTv(array $ignore = []): Collection
    {
        $query = Models\Category::with('tvs.templates')
            ->whereHas('tvs', function(Eloquent\Builder $builder) use
            (
                $ignore
            ) {
                if (!empty($ignore)) {
                    $builder = $builder->whereNotIn(
                        (new Models\SiteTmplvar)->getTable() . '.id'
                        , $ignore
                    );
                }
                return $builder;
            })
            ->orderBy('rank', 'ASC');

        return $query->get();
    }

    protected function parameterEvents(): array
    {
        $out = [];

        foreach ($this->events as $event) {
            $out[$event] = $this->callEvent($event);
        }

        return $out;
    }

    private function callEvent($name): string
    {
        $out = $this->managerTheme->getCore()->invokeEvent($name, [
            'id' => $this->getElementId(),
            'controller' => $this
        ]);
        if (\is_array($out)) {
            return implode('', $out);
        }

        return (string)$out;
    }

    protected function parameterActionButtons()
    {
        return [
            'select'    => 1,
            'save'      => $this->managerTheme->getCore()->hasPermission('save_template'),
            'new'       => $this->managerTheme->getCore()->hasPermission('new_template'),
            'duplicate' => $this->object->getKey() && $this->managerTheme->getCore()->hasPermission('new_template'),
            'delete'    => $this->object->getKey() && $this->managerTheme->getCore()->hasPermission('delete_template'),
            'cancel'    => 1
        ];
    }
}
