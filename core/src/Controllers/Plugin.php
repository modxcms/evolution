<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;

class Plugin extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.plugin';

    protected $events = [
        'OnPluginFormPrerender',
        'OnPluginFormRender'
    ];

    /** @var Models\SitePlugin|null */
    private $data;

    protected $internal;

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(102, $this->getElementId())
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
            case 101:
                $out = evolutionCMS()->hasPermission('new_plugin');
                break;

            case 102:
                $out = evolutionCMS()->hasPermission('edit_plugin');
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
            'internal' => $this->internal,
            'events' => $this->parameterEvents(),
            'actionButtons' => $this->parameterActionButtons()
        ];
    }

    /**
     * @return Models\SitePlugin
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SitePlugin $data */
        $data = Models\SitePlugin::firstOrNew(['id' => $id]);

        if ($data->exists) {
            if (empty($data->count())) {
                evolutionCMS()->webAlertAndQuit('Plugin not found for id ' . $id . '.');
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

        $ds = evolutionCMS()
            ->getDatabase()
            ->select('sm.id,sm.name,sm.guid', evolutionCMS()
                    ->getDatabase()
                    ->getFullTableName("site_modules") . " sm
					INNER JOIN " . evolutionCMS()
                    ->getDatabase()
                    ->getFullTableName("site_module_depobj") . " smd ON smd.module=sm.id AND smd.type=30
					INNER JOIN " . evolutionCMS()
                    ->getDatabase()
                    ->getFullTableName("site_plugins") . " 
                    sp ON sp.id=smd.resource", "smd.resource='{$this->data->getKey()}' AND sm.enable_sharedparams='1'",
                'sm.name');
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
        $internal = array();
        if (isset($this->data->plugincode)) {
            $snippetcode = evolutionCMS()
                ->getDatabase()
                ->escape($this->data->plugincode);
            $parsed = evolutionCMS()->parseDocBlockFromString($snippetcode);
            $out = evolutionCMS()->convertDocBlockIntoList($parsed);
            $internal[0]['events'] = isset($parsed['events']) ? $parsed['events'] : '';
        }

        $this->internal = json_encode($internal);

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
            'save' => evolutionCMS()->hasPermission('save_plugin'),
            'new' => evolutionCMS()->hasPermission('new_plugin'),
            'duplicate' => !empty($this->data->getKey()) && evolutionCMS()->hasPermission('new_plugin'),
            'delete' => !empty($this->data->getKey()) && evolutionCMS()->hasPermission('delete_plugin'),
            'cancel' => 1
        ];
    }
}
