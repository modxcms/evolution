<?php namespace EvolutionCMS\Controllers\UserRoles;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Legacy\Permissions;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class PermissionsGroups extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.user_roles.permissions_groups';

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
        return $this->managerTheme->getCore()->hasPermission('edit_role');
    }

    public function process(): bool
    {
        if (isset($_GET['action']) && $_GET['action'] == 'delete') {
            Models\Permissions::query()->where('group_id', $this->getElementId())->delete();
            Models\PermissionsGroups::query()->where('id', $this->getElementId())->delete();
            header('Location: index.php?a=86&tab=1');
        }
        if (isset($_POST['a'])) {
            $this->updateOrCreate();
            return true;
        }

        return true;
    }

    public function updateOrCreate()
    {
        $id = $this->getElementId();
        $group = Models\PermissionsGroups::query()->firstOrNew(['id' => $id]);
        $group->name = $_POST['name'];
        $group->lang_key = $_POST['lang_key'];
        $group->save();
        header('Location: index.php?a=136&id=' . $group->getKey() . '&r=9');
    }

    public static function findCategoryOrNew($name)
    {
        $group = Models\PermissionsGroups::query()->firstOrNew(['name' => $name]);
        $group->save();
        return $group->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        $id = $this->getElementId();
        return [
            'groups' => Models\PermissionsGroups::findOrNew($id)
        ];
    }
}
