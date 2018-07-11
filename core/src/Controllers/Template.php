<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

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
        return [
            'data' => $this->parameterData(),
            'categories' => $this->parameterCategories(),
            'tvs' => $this->parameterTvs(),
            'action' => $this->getIndex(),
            'Events' => $this->parameterEvents()
        ];
    }

    /**
     * @return Models\SiteTemplate
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SiteTemplate $data */
        $data = Models\SiteTemplate::firstOrNew(
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

    protected function parameterCategories() : array
    {
        return Models\Category::orderBy('rank', 'ASC')->orderBy('category', 'ASC')->pluck('category', 'id');
    }

    protected function parameterTvs()
    {
        $out = [];
        $id = $this->getElementId();

        $selectedTvs = array();
        if (!isset($_POST['assignedTv'])) {
            $rs = evolutionCMS()
                ->getDatabase()
                ->select(sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category",
                    $this->managerTheme->getLexicon('no_category')), sprintf("%s tv
                LEFT JOIN %s tr ON tv.id=tr.tmplvarid
                LEFT JOIN %s cat ON tv.category=cat.id", evolutionCMS()
                    ->getDatabase()
                    ->getFullTableName('site_tmplvars'), evolutionCMS()
                    ->getDatabase()
                    ->getFullTableName('site_tmplvar_templates'), evolutionCMS()
                    ->getDatabase()
                    ->getFullTableName('categories')), "templateid='{$id}'",
                    "tr.rank DESC, tv.rank DESC, tvcaption DESC, tvid DESC"     // workaround for correct sort of none-existing ranks
                );
            while ($row = evolutionCMS()
                ->getDatabase()
                ->getRow($rs)) {
                $selectedTvs[$row['tvid']] = $row;
            }
            $selectedTvs = array_reverse($selectedTvs, true);       // reverse ORDERBY DESC
        }

        $unselectedTvs = array();
        $rs = evolutionCMS()
            ->getDatabase()
            ->select(sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category, cat.id as catid",
                $this->managerTheme->getLexicon('no_category')), sprintf("%s tv
	    LEFT JOIN %s tr ON tv.id=tr.tmplvarid
	    LEFT JOIN %s cat ON tv.category=cat.id", evolutionCMS()
                ->getDatabase()
                ->getFullTableName('site_tmplvars'), evolutionCMS()
                ->getDatabase()
                ->getFullTableName('site_tmplvar_templates'), evolutionCMS()
                ->getDatabase()
                ->getFullTableName('categories')), "", "category, tvcaption");

        while ($row = evolutionCMS()
            ->getDatabase()
            ->getRow($rs)) {
            $unselectedTvs[$row['tvid']] = $row;
        }

        // Catch checkboxes if form not validated
        if (isset($_POST['assignedTv'])) {
            $selectedTvs = array();
            foreach ($_POST['assignedTv'] as $tvid) {
                if (isset($unselectedTvs[$tvid])) {
                    $selectedTvs[$tvid] = $unselectedTvs[$tvid];
                }
            };
        }

        $out['selectedTvs'] = $selectedTvs;
        $out['unselectedTvs'] = $unselectedTvs;
        $out['total_selected'] = count($selectedTvs);
        $out['total_unselected'] = count(array_diff_key($unselectedTvs, $selectedTvs));

        return $out;
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
