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
    private $data;

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function canView(): bool
    {
        switch ($this->getIndex()) {
            case 22:
                $out = evolutionCMS()->hasPermission('edit_snippet');
                break;

            case 23:
                $out = evolutionCMS()->hasPermission('new_snippet');
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
        $this->data = $this->parameterData();
        return [
            'data' => $this->data,
            'categories' => $this->parameterCategories(),
            'action' => $this->getIndex(),
            'importParams' => $this->parameterImportParams(),
            'docBlockList' => $this->parameterDocBlockList(),
            'events' => $this->parameterEvents(),
            'actionButtons' => $this->parameterActionButtons()
        ];
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
                evolutionCMS()->webAlertAndQuit('Snippet not found for id ' . $id . '.');
            }

            $_SESSION['itemname'] = $data->name;
            if ($data->locked === 1 && $_SESSION['mgrRole'] != 1) {
                evolutionCMS()->webAlertAndQuit($this->managerTheme->getLexicon("error_no_privileges"));
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
        $tbl_site_module_depobj = evolutionCMS()
            ->getDatabase()
            ->getFullTableName('site_module_depobj');
        $tbl_site_modules = evolutionCMS()
            ->getDatabase()
            ->getFullTableName('site_modules');
        $tbl_site_snippets = evolutionCMS()
            ->getDatabase()
            ->getFullTableName('site_snippets');

        $ds = evolutionCMS()
            ->getDatabase()
            ->select('sm.id,sm.name,sm.guid', "{$tbl_site_modules} AS sm
            INNER JOIN {$tbl_site_module_depobj} AS smd ON smd.module=sm.id AND smd.type=40 
            INNER JOIN {$tbl_site_snippets} AS ss ON ss.id=smd.resource", "smd
            .resource='{$this->data->getKey()}' AND sm.enable_sharedparams=1", 'sm.name');

        while ($row = evolutionCMS()
            ->getDatabase()
            ->getRow($ds)) {
            $out[$row['guid']] = $row['name'];
        }

        return $out;
    }

    protected function parameterDocBlockList()
    {
        $out = '';
        if (isset($this->data->snippet)) {
            $snippetcode = evolutionCMS()
                ->getDatabase()
                ->escape($this->data->snippet);
            $parsed = evolutionCMS()->parseDocBlockFromString($snippetcode);
            $out = evolutionCMS()->convertDocBlockIntoList($parsed);
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
        $out = evolutionCMS()->invokeEvent($name, [
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
            'save' => evolutionCMS()->hasPermission('save_snippet'),
            'new' => evolutionCMS()->hasPermission('new_snippet'),
            'duplicate' => !empty($this->data->getKey()) && evolutionCMS()->hasPermission('new_snippet'),
            'delete' => !empty($this->data->getKey()) && evolutionCMS()->hasPermission('delete_snippet'),
            'cancel' => 1
        ];
    }
}
