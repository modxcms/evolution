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
    public function process() : bool
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
        $out = [];

        // Get table Names (alphabetical)
        $tbl_site_module_depobj = $this->managerTheme->getCore()
            ->getDatabase()
            ->getFullTableName('site_module_depobj');
        $tbl_site_modules = $this->managerTheme->getCore()
            ->getDatabase()
            ->getFullTableName('site_modules');
        $tbl_site_snippets = $this->managerTheme->getCore()
            ->getDatabase()
            ->getFullTableName('site_snippets');

        $ds = $this->managerTheme->getCore()
            ->getDatabase()
            ->select('sm.id,sm.name,sm.guid', "{$tbl_site_modules} AS sm
            INNER JOIN {$tbl_site_module_depobj} AS smd ON smd.module=sm.id AND smd.type=40 
            INNER JOIN {$tbl_site_snippets} AS ss ON ss.id=smd.resource", "smd
            .resource='{$this->object->getKey()}' AND sm.enable_sharedparams=1", 'sm.name');

        while ($row = $this->managerTheme->getCore()
            ->getDatabase()
            ->getRow($ds)) {
            $out[$row['guid']] = $row['name'];
        }

        return $out;
    }

    protected function parameterDocBlockList()
    {
        $out = '';
        if (isset($this->object->snippet)) {
            $snippetcode = $this->managerTheme->getCore()
                ->getDatabase()
                ->escape($this->object->snippet);
            $parsed = $this->managerTheme->getCore()->parseDocBlockFromString($snippetcode);
            $out = $this->managerTheme->getCore()->convertDocBlockIntoList($parsed);
        }

        return $out;
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
            $out = implode('', $out);
        }

        return (string)$out;
    }

    protected function parameterActionButtons()
    {
        return [
            'select' => 1,
            'save' => $this->managerTheme->getCore()->hasPermission('save_snippet'),
            'new' => $this->managerTheme->getCore()->hasPermission('new_snippet'),
            'duplicate' => !empty($this->object->getKey()) && $this->managerTheme->getCore()->hasPermission('new_snippet'),
            'delete' => !empty($this->object->getKey()) && $this->managerTheme->getCore()->hasPermission('delete_snippet'),
            'cancel' => 1
        ];
    }
}
