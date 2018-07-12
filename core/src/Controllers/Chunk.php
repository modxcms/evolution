<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;

class Chunk extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.chunk';

    protected $events = [
        'OnChunkFormPrerender',
        'OnChunkFormRender',
        'OnRichTextEditorRegister',
        'OnRichTextEditorInit'
    ];

    /** @var Models\SiteHtmlsnippet|null */
    private $data;

    protected $which_editor;

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(78, $this->getElementId())
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
            case 77:
                $out = evolutionCMS()->hasPermission('new_chunk');
                break;

            case 78:
                $out = evolutionCMS()->hasPermission('edit_chunk');
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
            'which_editor' => $this->which_editor,
            'action' => $this->getIndex(),
            'events' => $this->parameterEvents()
        ];
    }

    /**
     * @return Models\SiteHtmlsnippet
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SiteHtmlsnippet $data */
        $data = Models\SiteHtmlsnippet::firstOrNew(['id' => $id]);

        if ($data->exists) {
            if (empty($data->count())) {
                evolutionCMS()->webAlertAndQuit('Chunk not found for id ' . $id . '.');
            }

            $_SESSION['itemname'] = $data->name;
            if ($data->locked === 1 && $_SESSION['mgrRole'] != 1) {
                evolutionCMS()->webAlertAndQuit($this->managerTheme->getLexicon("error_no_privileges"));
            }
        } elseif (isset($_REQUEST['itemname'])) {
            $data->name = $_REQUEST['itemname'];
        } else {
            $_SESSION['itemname'] = $this->managerTheme->getLexicon("new_htmlsnippet");
        }

        $values = $this->managerTheme->loadValuesFromSession($_POST);

        if (!empty($values)) {
            $data->fill($values);
            $this->which_editor = isset($values['which_editor']) ? $values['which_editor'] : 'none';
        }


        return $data;
    }

    protected function parameterCategories(): Collection
    {
        return Models\Category::orderBy('rank', 'ASC')
            ->orderBy('category', 'ASC')
            ->get();
    }

    protected function parameterEvents(): array
    {
        $out = [];

        foreach ($this->events as $event) {
            $out[$event] = $this->callEvent($event);
        }

        return $out;
    }

    private function callEvent($name)
    {
        switch ($name) {
            case 'OnRichTextEditorRegister':
                $out = $this->callEventOnRichTextEditorRegister();
                break;

            case 'OnRichTextEditorInit':
                $out = $this->callEventOnRichTextEditorInit();
                break;

            default:
                $out = $this->callEventDefault($name);
        }

        return $out;
    }

    protected function callEventOnRichTextEditorRegister()
    {
        $out = evolutionCMS()->invokeEvent('OnRichTextEditorRegister', [
            'controller' => $this
        ]);
        if (empty($out) && !is_array($out)) {
            $out = [];
        }

        return (array)$out;
    }

    protected function callEventOnRichTextEditorInit()
    {
        if (!empty($this->which_editor)) {
            $which_editor = $this->which_editor;
        } else {
            $which_editor = $this->data->editor_name != 'none' ? $this->data->editor_name : 'none';
        }
        $out = evolutionCMS()->invokeEvent('OnRichTextEditorInit', [
            'editor' => $which_editor,
            'elements' => ['post'],
            'controller' => $this
        ]);
        if (\is_array($out)) {
            $out = implode('', $out);
        }

        return (string)$out;
    }

    protected function callEventDefault($name)
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
