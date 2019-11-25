<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use EvolutionCMS\Models;

class AccessPermissions extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.access_permissions';

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
        return $this->managerTheme->getCore()->hasPermission('access_permissions');
    }

    public function process() : bool
    {
        $this->parameters['userGroups'] = Models\MembergroupName::with(['users', 'documentGroups'])
            ->orderBy('name', 'ASC')
            ->get();

        $this->parameters['documentGroups'] = Models\DocumentgroupName::with('documents')
            ->orderBy('name', 'ASC')
            ->get();

        return true;
    }
}
