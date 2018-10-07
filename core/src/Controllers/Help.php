<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class Help extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.help';

    protected $helpBasePath = 'actions/help/';

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
        return $_SESSION['mgrRole'] != 1 ? $this->managerTheme->getCore()->hasPermission('help') : true;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        return [
            'pages' => $this->parameterPages()
        ];
    }

    protected function parameterPages()
    {
        $pages = [];

        if ($handle = opendir($this->helpBasePath)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != ".svn" && $file != 'index.html' && !is_dir($this->helpBasePath . $file)) {
                    $name = substr($file, 0, strrpos($file, '.'));
                    $prefix = substr($name, 0, 2);
                    if (is_numeric($prefix)) {
                        $name = substr($name, 2, strlen($name) - 1);
                    }
                    $hnLower = strtolower($name);
                    $name = isset($_lang[$hnLower]) ? $this->managerTheme->getLexicon($hnLower) : str_replace('_', ' ',
                        $name);
                    $pages[$file] = [
                        'name' => $name,
                        'path' => $this->helpBasePath . $file
                    ];
                }
            }
            closedir($handle);
        }
        sort($pages);

        return $pages;
    }
}
