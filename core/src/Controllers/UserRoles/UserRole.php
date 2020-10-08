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
        if (!$this->managerTheme->getCore()->hasPermission('edit_role')) {
            return false;
        }
        if (!$this->managerTheme->getCore()->hasPermission('new_role')) {
            return false;
        }
        return true;
    }

    public function process(): bool
    {
        if(isset($_GET['action']) && $_GET['action'] == 'delete' ){
            Models\RolePermissions::query()->where('role_id', $this->getElementId())->delete();
            Models\UserRole::query()->where('id', $this->getElementId())->delete();
            header('Location: index.php?a=86&tab=0');
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
        $mode = $this->getIndex();
        if (!$this->managerTheme->getCore()->hasPermission('save_role')) {
            $this->managerTheme->alertAndQuit('error_no_privileges');
        }
        if (!isset($_POST['name']) || $_POST['name'] == '') {
            $this->managerTheme->getCore()->getManagerApi()->saveFormValues();
            $this->managerTheme->getCore()->webAlertAndQuit("Please enter a name for this role!", "index.php?a={$mode}" . ($mode = 35 ? "&id={$id}" : ""));
        }

        $role = Models\UserRole::findOrNew($id);
        $role->name = $_POST['name'];
        $role->description = $_POST['description'];
        $role->save();
        if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
            Models\RolePermissions::query()->where('role_id', $role->getKey())->delete();
            foreach ($_POST['permissions'] as $key => $permission) {
                Models\RolePermissions::create(['role_id' => $role->getKey(), 'permission' => $key]);
            }
        }
        $this->managerTheme->getCore()->getManagerApi()->clearSavedFormValues();
        header('Location: index.php?a=35&id=' . $role->getKey() . '&r=9');
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        $this->managerTheme->getCore()->getManagerApi()->loadFormValues();

        $id = $this->getElementId();
        $permissionsRole = [];
        if ($id != 0) {
            $permissionsRoleTemp = Models\RolePermissions::query()->where('role_id', $id)->get()->pluck('permission')->toArray();
            foreach ($permissionsRoleTemp as $role) {
                $permissionsRole[$role] = 1;
            }
        }
        if(isset($_POST['a'])){
            foreach ($_POST['permissions'] as $role => $key) {
                $permissionsRole[$role] = 1;
            }
        }
        return [
            'role' => Models\UserRole::findOrNew($id),
            'groups' => Models\PermissionsGroups::query()->get(),
            'permissionsRole' => $permissionsRole
        ];
    }
}
