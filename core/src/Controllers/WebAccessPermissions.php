<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use EvolutionCMS\Models;

class WebAccessPermissions extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.web_access_permissions';

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
        return $this->managerTheme->getCore()->hasPermission('web_access_permissions');
    }

    public function process() : bool
    {
        $this->parameters['userGroups'] = Models\WebgroupName::with(['users', 'documentGroups'])
            ->orderBy('name', 'ASC')
            ->get();

        $this->parameters['documentGroups'] = Models\DocumentgroupName::with('documents')
            ->orderBy('name', 'ASC')
            ->get();

        return true;
    }
}
