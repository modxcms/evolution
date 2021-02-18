<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;

class Snippet extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.snippet';

    protected $events = [
        'OnSnipFormPrerender',
        'OnSnipFormRender'
    ];

    /** @var Models\SiteSnippet|null */
    private $object;

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(22, $this->getElementId())
            ->first();
        if ($out !== null) {
            $out = sprintf($this->managerTheme->getLexicon('error_no_privileges'), $out->username);
        }

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        switch ($this->getIndex()) {
            case 22:
                $out = $this->managerTheme->getCore()->hasPermission('edit_snippet');
                break;

            case 23:
                $out = $this->managerTheme->getCore()->hasPermission('new_snippet');
                break;

            default:
                $out = false;
        }

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function process(): bool
    {
        $this->object = $this->parameterData();

        $this->parameters = [
            'data' => $this->object,
            'categories' => $this->parameterCategories(),
            'action' => $this->getIndex(),
            'importParams' => $this->parameterImportParams(),
            'docBlockList' => $this->parameterDocBlockList(),
            'events' => $this->parameterEvents(),
            'actionButtons' => $this->parameterActionButtons()
        ];

        return true;
    }

    /**
     * @return Models\SiteSnippet
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SiteSnippet $data */
        $data = Models\SiteSnippet::firstOrNew(['id' => $id]);

        if ($data->exists) {
            if (empty($data->count())) {
                $this->managerTheme->alertAndQuit('Snippet not found for id ' . $id . '.', false);
            }

            $_SESSION['itemname'] = $data->name;
            if ($data->locked === 1 && $_SESSION['mgrRole'] != 1) {
                $this->managerTheme->alertAndQuit('error_no_privileges');
            }
        } elseif (isset($_REQUEST['itemname'])) {
            $data->name = $_REQUEST['itemname'];
        } else {
            $_SESSION['itemname'] = $this->managerTheme->getLexicon("new_snippet");
        }

        $values = $this->managerTheme->loadValuesFromSession($_POST);

        if (!empty($values)) {
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

    protected function parameterImportParams()
    {
        return Models\SiteModule::query()->join('site_module_depobj', function ($join) {
            $join->on('site_module_depobj.module', '=', 'site_modules.id');
            $join->on('site_module_depobj.type', '=', \DB::raw(40));
        })->join('site_snippets', 'site_snippets.id', '=', 'site_module_depobj.resource')
            ->where('site_module_depobj.resource', $this->object->getKey())
            ->where('site_modules.enable_sharedparams', 1)->orderBy('site_modules.name', 'ASC')->get()
            ->pluck('name', 'guid')->toArray();
    }

    protected function parameterDocBlockList()
    {
        if (!isset($this->object->snippet)) {
            return '';
        }

        return $this->managerTheme->getCore()->convertDocBlockIntoList(
            $this->managerTheme->getCore()->parseDocBlockFromString(
                $this->object->snippet
            )
        );
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
            'select' => 1,
            'save' => $this->managerTheme->getCore()->hasPermission('save_snippet'),
            'new' => $this->managerTheme->getCore()->hasPermission('new_snippet'),
            'duplicate' => $this->object->getKey() && $this->managerTheme->getCore()->hasPermission('new_snippet'),
            'delete' => $this->object->getKey() && $this->managerTheme->getCore()->hasPermission('delete_snippet'),
            'cancel' => 1
        ];
    }
}
