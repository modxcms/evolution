<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Legacy\ManagerApi;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class Template extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.template';

    protected $Events = [
        'OnTempFormPrerender',
        'OnTempFormRender'
    ];

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(16)
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
        switch ((new ManagerApi)->action) {
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
            'action' => (new ManagerApi)->action,
            'Events' => $this->parameterEvents()
        ];
    }

    private function parameterData()
    {
        $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
        $data = (object)[];
        if (!empty($id)) {
            $data = Models\SiteTemplate::where('id', '=', $id)
                ->get();

            if (empty($data[0])) {
                evolutionCMS()->webAlertAndQuit("No database record has been found for this template.");
            }

            $data = $data[0];
            $_SESSION['itemname'] = $data->templatename;
            if ($data->locked == 1 && $_SESSION['mgrRole'] != 1) {
                evolutionCMS()->webAlertAndQuit(\ManagerTheme::getLexicon("error_no_privileges"));
            }
        } else {
            $_SESSION['itemname'] = \ManagerTheme::getLexicon("new_template");
            $data->id = 0;
            $data->templatename = '';
            $data->description = '';
            $data->selectable = 1;
            $data->content = '';
            $data->locked = '';
            $data->category = isset($_REQUEST['catid']) ? (int)$_REQUEST['catid'] : '';
        }

        if (evolutionCMS()
            ->getManagerApi()
            ->hasFormValues()) {
            evolutionCMS()
                ->getManagerApi()
                ->loadFormValues();
        }

        if (!empty($_POST)) {
            foreach ($_POST as $k => $v) {
                if (isset($data->{$k})) {
                    $data->{$k} = $v;
                }
            }
        }

        return $data;
    }

    private function parameterCategories()
    {
        $data = [];
        $res = Models\Category::get();
        if (!empty($res)) {
            foreach ($res as $k => $v) {
                $data[$v['id']] = $v['category'];
            }
        }

        return $data;
    }

    private function parameterTvs()
    {
        $out = [];
        $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

        $selectedTvs = array();
        if (!isset($_POST['assignedTv'])) {
            $rs = evolutionCMS()
                ->getDatabase()
                ->select(sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category",
                    \ManagerTheme::getLexicon('no_category')), sprintf("%s tv
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
                \ManagerTheme::getLexicon('no_category')), sprintf("%s tv
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

    private function parameterEvents()
    {
        $out = [];

        foreach ($this->Events as $event) {
            $out[$event] = $this->callEvent($event);
        }

        return $out;
    }

    private function callEvent($name)
    {
        $out = evolutionCMS()->invokeEvent($name);
        if (\is_array($out)) {
            $out = implode('', $out);
        }

        return $out;
    }
}
