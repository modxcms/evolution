<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Support\Collection;

class Tmplvar extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.tmplvar';

    private $events = [
        'OnRichTextEditorRegister',
        'OnTVFormPrerender',
        'OnTVFormRender',
    ];

    /**
     * @inheritdoc
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(301, $this->getElementId())
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
            case 300:
                $out = evolutionCMS()->hasPermission('new_template');
                break;

            case 301:
                $out = evolutionCMS()->hasPermission('edit_template');
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
            'types' => $this->parameterTypes(),
            'display' => $this->parameterDisplay(),
            'tplSelected' => $this->parameterTplSelected(),
            'categoriesWithTpl' => $this->parameterCategoriesWithTpl(),
            'tplOutCategory' => $this->parameterTplOutCategory(),
            'action' => $this->getIndex(),
            'events' => $this->parameterEvents(),
            // :TODO delete
            'origin' => isset($_REQUEST['or']) ? (int)$_REQUEST['or'] : 76,
            'originId' => isset($_REQUEST['oid']) ? (int)$_REQUEST['oid'] : NULL
        ];
    }

    /**
     * @return Models\SiteTmplvar
     */
    protected function parameterData()
    {
        $id = $this->getElementId();

        /** @var Models\SiteTmplvar $data */
        $data = Models\SiteTmplvar::with('templates')
            ->firstOrNew(['id' => $id], [
                'category' => (int)get_by_key($_REQUEST, 'catid', 0)
            ]);

        if ($id > 0) {
            $_SESSION['itemname'] = $data->caption;
            if ($data->locked == 1 && $_SESSION['mgrRole'] != 1) {
                evolutionCMS()->webAlertAndQuit($this->managerTheme->getLexicon("error_no_privileges"));
            }
        } elseif (isset($_REQUEST['itemname'])) {
            $data->name = $_REQUEST['itemname'];
        } else {
            $_SESSION['itemname'] = $this->managerTheme->getLexicon("new_template");
            $data->category = isset($_REQUEST['catid']) ? (int)$_REQUEST['catid'] : 0;
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

    protected function parameterTypes()
    {

        $custom_tvs = [
            'custom_tv' => 'Custom Input'
        ];

        foreach (scandir(MODX_BASE_PATH . 'assets/tvs') as $ctv) {
            if (strpos($ctv, '.') !== 0 && $ctv != 'index.html') {
                $custom_tvs['custom_tv:' . $ctv] = $ctv;
            }
        }

        return [
            0 => [
                'optgroup' => [
                    'name' => 'Standard Type',
                    'options' => [
                        'text' => 'Text',
                        'rawtext' => 'Raw Text (deprecated)',
                        'textarea' => 'Textarea',
                        'rawtextarea' => 'Raw Textarea (deprecated)',
                        'textareamini' => 'Textarea (Mini)',
                        'richtext' => 'RichText',
                        'dropdown' => 'DropDown List Menu',
                        'listbox' => 'Listbox (Single-Select)',
                        'listbox-multiple' => 'Listbox (Multi-Select)',
                        'option' => 'Radio Options',
                        'checkbox' => 'Check Box',
                        'image' => 'Image',
                        'file' => 'File',
                        'url' => 'URL',
                        'email' => 'Email',
                        'number' => 'Number',
                        'date' => 'Date'
                    ]
                ]
            ],
            1 => [
                'optgroup' => [
                    'name' => 'Custom Type',
                    'options' => $custom_tvs
                ]
            ]
        ];
    }

    protected function parameterDisplay()
    {
        return [
            0 => [
                'optgroup' => [
                    'name' => 'Widgets',
                    'options' => [
                        'datagrid' => 'Data Grid',
                        'richtext' => 'RichText',
                        'viewport' => 'View Port',
                        'custom_widget' => 'Custom Widget'
                    ]
                ]
            ],
            1 => [
                'optgroup' => [
                    'name' => 'Formats',
                    'options' => [
                        'htmlentities' => 'HTML Entities',
                        'date' => 'Date Formatter',
                        'unixtime' => 'Unixtime',
                        'delim' => 'Delimited List',
                        'htmltag' => 'HTML Generic Tag',
                        'hyperlink' => 'Hyperlink',
                        'image' => 'Image',
                        'string' => 'String Formatter'
                    ]
                ]
            ]
        ];
    }

    protected function parameterTplSelected()
    {
        return array_unique(array_map('intval', get_by_key($_POST, 'template', [], 'is_array')));
    }

    protected function parameterTplOutCategory(): Collection
    {
        return Models\SiteTemplate::with('tvs')
            ->where('category', '=', 0)
            ->orderBy('templatename', 'ASC')
            ->get();
    }

    protected function parameterCategoriesWithTpl(): Collection
    {
        return Models\Category::with('templates.tvs')
            ->whereHas('templates')
            ->orderBy('rank', 'ASC')
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

    private function callEvent($name): string
    {
        $out = evolutionCMS()->invokeEvent($name, [
            'id' => $this->getElementId(),
            'controller' => $this,
            'forfrontend' => 1
        ]);
        if (\is_array($out)) {
            $out = implode('', $out);
        }

        return (string)$out;
    }
}
