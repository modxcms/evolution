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
        if ($_SESSION['mgrRole'] != 1) {
            return $this->managerTheme->getCore()->hasPermission('help');
        }

        return true;
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

        $help = glob($this->helpBasePath . '*.phtml');
        natcasesort($help);

        foreach ($help as $file) {
            $fileName = basename($file, '.phtml');
            preg_match('/^(\d+)(.*)$/', $fileName, $prefix);
            if (isset($prefix[1]) && is_numeric($prefix[1])) {
                $helpname = $prefix[2];
            } else {
                $helpname = $fileName;
            }

            $pages[$file] = [
                'name' => $this->managerTheme->getLexicon(strtolower($helpname), str_replace('_', ' ', $helpname)),
                'path' => $file
            ];
        }

        return $pages;
    }
}
