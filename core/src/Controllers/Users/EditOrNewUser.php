<?php namespace EvolutionCMS\Controllers\Users;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class EditOrNewUser extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.users.message_after_save';

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
        return $this->managerTheme->getCore()->hasPermission('save_user');
    }

    public function process(): bool
    {
        $id = false;
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }
        if ($_POST['newpassword'] == 1) {
            if ($_POST['passwordgenmethod'] == 'g') {
                $_POST['password_confirmation'] = $_POST['password'] = generate_password(8);

            } else {
                $_POST['password'] = $_POST['specifiedpassword'];
                $_POST['password_confirmation'] = $_POST['confirmpassword'];
            }
        }
        $_POST['username'] = $_POST['newusername'];
        try {
            if ($_POST['mode'] == 87) {
                $user = \UserManager::create($_POST);
            } else {
                $user = \UserManager::edit($_POST);
            }
        } catch (\EvolutionCMS\Exceptions\ServiceValidationException $exception) {
            foreach ($exception->getValidationErrors() as $errors) {
                foreach ($errors as $error) {
                    webAlertAndQuit($error, $_POST['mode'], $id);
                    exit();
                }
            }
            exit();
        }
        // Save User Settings
        saveUserSettings($user->getKey());

        if (isset($_POST['role'])
            && $_POST['role'] != $user->attributes->role
            && EvolutionCMS()->hasPermission('save_role')) {
            \UserManager::setRole(['id' => $user->getKey(), 'role' => $_POST['role']]);
        }

        if (isset($_POST['user_groups']) && is_array($_POST['user_groups'])) {
            \UserManager::setGroups(['id' => $user->getKey(), 'groups' => $_POST['user_groups']]);
        }

        if ($_POST['stay'] != '') {
            $a = ($_POST['stay'] == '2') ? "88&id={$user->getKey()}" : "87";
            $this->parameters['url'] = "index.php?a={$a}&r=2&stay=" . $_POST['stay'];
        } else {
            $this->parameters['url'] = "index.php?a=99&r=2";
        }
        if ($_POST['passwordnotifymethod'] == 'e') {
            $websignupemail_message = EvolutionCMS()->getConfig('websignupemail_message');
            $site_url = EvolutionCMS()->getConfig('site_url');
            sendMailMessageForUser($user->attributes->email, $user->username, $_POST['password'], $user->attributes->fullname, $websignupemail_message, $site_url);

        }
        if ($_POST['passwordnotifymethod'] == 's' && $_POST['newpassword'] == 1) {
            $this->parameters['username'] = $user->username;
            $this->parameters['password'] = $_POST['password'];
            return true;
        }
        header("Location: " . $this->parameters['url']);
        exit();
    }
}
