<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;

class Template extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.template';

    private $events = [
        'OnTempFormPrerender',
        'OnTempFormRender'
    ];

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(16, $this->getElementId())
            ->first();
        if ($out !== null) {
            $out = sprintf($this->managerTheme->getLexicon('error_no_privileges'), $out->username);
        }

        return $out;
    }

    /**
     * @inheritdoc
     */
    public function canView(): bool
    {
        switch ($this->getIndex()) {
            case 16:
                $out = evolutionCMS()->hasPermission('edit_template');
                break;

            case 19:
                $out = evolutionCMS()->hasPermission('new_template');
                break;

            default:
                $out = false;
        }

        return $out;
    }

    /**
     * @inheritdoc
     */
    public function getParameters(array $params = []): array
    {
        $template = $this->parameterData();
        return [
            'data' => $template,
            'categories' => $this->parameterCategories(),
            'tvSelected' => $this->parameterTvSelected(),
            'categoriesWithTv' => $this->parameterCategoriesWithTv(),
            'tvOutCategory' => $this->parameterTvOutCategory(),
            'action' => $this->getIndex(),
            'events' => $this->parameterEvents()
        ];
    }

    /**
     * @return Models\SiteTemplate
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SiteTemplate $data */
        $data = Models\SiteTemplate::with('tvs')->firstOrNew(
            ['id' => $id],
            [
                'category' => (int)get_by_key($_REQUEST, 'catid', 0),
                'selectable' => 1
            ]
        );

        if ($id > 0) {
            if (! $data->exists) {
                evolutionCMS()->webAlertAndQuit("No database record has been found for this template.");
            }

            $_SESSION['itemname'] = $data->templatename;
            if ($data->locked == 1 && $_SESSION['mgrRole'] != 1) {
                evolutionCMS()->webAlertAndQuit($this->managerTheme->getLexicon("error_no_privileges"));
            }
        } else {
            $_SESSION['itemname'] = $this->managerTheme->getLexicon("new_template");
        }

        $values = $this->managerTheme->loadValuesFromSession($_POST);

        if (!empty($values)) {
            $data->fill($values);
        }

        return $data;
    }

    protected function parameterCategories() : Collection
    {
        return Models\Category::orderBy('rank', 'ASC')->orderBy('category', 'ASC')->get();
    }

    protected function parameterTvSelected()
    {
        return array_unique(
            array_map(
                'intval',
                get_by_key($_POST, 'assignedTv', [], 'is_array')
            )
        );
    }

    protected function parameterTvOutCategory(): Collection
    {
        return Models\SiteTmplvar::with('templates')
            ->where('category', '=', 0)
            ->orderBy('name', 'ASC')
            ->get();
    }

    protected function parameterCategoriesWithTv(): Collection
    {
        return Models\Category::with('tvs.templates')
            ->whereHas('tvs')
            ->orderBy('rank', 'ASC')
            ->get();
    }

    protected function parameterEvents() : array
    {
        $out = [];

        foreach ($this->events as $event) {
            $out[$event] = $this->callEvent($event);
        }

        return $out;
    }

    private function callEvent($name) : string
    {
        $out = evolutionCMS()->invokeEvent($name, [
            'id' => $this->getElementId(),
            'controller' => $this
        ]);
        if (\is_array($out)) {
            $out = implode('', $out);
        }

        return (string)$out;
    }
}
