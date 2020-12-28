<?php namespace EvolutionCMS\Controllers\UserRoles;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Controllers\AbstractResources;
use EvolutionCMS\Interfaces\ManagerTheme\TabControllerInterface;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class PermissionsList extends AbstractResources implements TabControllerInterface
{
    protected $view = 'page.user_roles.permission_list';

    /**
     * {@inheritdoc}
     */
    public function getTabName($withIndex = true): string
    {
        if ($withIndex) {
            return 'tabPermissions-'.$this->getIndex();
        }

        return 'tabPermissions';
    }

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
        $params = array_merge($this->getBaseParams(), $params);


        return array_merge([
            'groups' => Models\PermissionsGroups::orderBy('id')->get(),
        ], $params);

    }

    protected function getBaseParams()
    {
        return array_merge(
            parent::getParameters(),
            [
                'tabPageName'      => $this->getTabName(false),
                'tabIndexPageName' => $this->getTabName()
            ]
        );
    }
}
