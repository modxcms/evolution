<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Database\Eloquent;


class PluginPriority extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.plugin_priority';

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return $this->managerTheme->getCore()->hasPermission('save_plugin');
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        return parent::getParameters([
            'events' => $this->getEventsParameter()
        ]);
    }

    protected function getEventsParameter() : Eloquent\Collection
    {
        return Models\SystemEventname::with(
            [
                'plugins' => function (Eloquent\Relations\BelongsToMany $query) {
                    $query->orderBy('priority');
                }
            ]
        )->whereHas('plugins')->orderBy('name')->get();
    }

    public function process() : bool
    {
        $updateMsg = false;

        if (isset($_POST['listSubmitted'])) {
            $updateMsg = true;
            $tbl = $this->managerTheme->getCore()->getDatabase()->getFullTableName('site_plugin_events');

            foreach ($_POST as $listName => $listValue) {
                if ($listName === 'listSubmitted') {
                    continue;
                }
                $orderArray = explode(',', $listValue);
                $listName = ltrim($listName, 'list_');
                if (\count($orderArray) > 0) {
                    foreach ($orderArray as $key => $item) {
                        if ($item == '') {
                            continue;
                        }
                        $pluginId = ltrim($item, 'item_');
                        $this->managerTheme->getCore()->getDatabase()
                            ->update(
                                array('priority' => $key),
                                $tbl,
                                "pluginid='{$pluginId}' AND evtid='{$listName}'"
                            );
                    }
                }
            }
            // empty cache
            $this->managerTheme->getCore()->clearCache('full');
        }

        $this->parameters['updateMsg'] = $updateMsg;

        return true;
    }
}
