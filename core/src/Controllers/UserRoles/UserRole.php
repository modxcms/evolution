<?php namespace EvolutionCMS\Controllers\UserRoles;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class UserRole extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.user_roles.user_role';

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
        return $this->managerTheme->getCore()->hasPermission('edit_user');
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        $id = $this->getElementId();
        return [
            'role' => Models\UserRole::findOrNew($id)
        ];
    }
}
