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
        $userData = $_POST;
        $id = false;
        if (isset($userData['id'])) {
            $id = $userData['id'];
        }
        if ($userData['newpassword'] == 1) {
            if ($userData['passwordgenmethod'] == 'g') {
                $userData['password_confirmation'] = $userData['password'] = generate_password(8);

            } else {
                $userData['password'] = $userData['specifiedpassword'];
                $userData['password_confirmation'] = $userData['confirmpassword'];
            }
        }
        $userData['username'] = $userData['newusername'];

        try {
            if ($userData['mode'] == 87) {
                $user = \UserManager::create($userData);
            } else {
                $user = \UserManager::edit($userData);
                if (isset($userData['password'])) {
                    $userData['clearPassword'] = $userData['password'];
                    $user->password =  EvolutionCMS()->getPasswordHash()->HashPassword($userData['password']);
                    $user->cachepwd = '';
                    $user->save();
                }
            }
        } catch (\EvolutionCMS\Exceptions\ServiceValidationException $exception) {
            foreach ($exception->getValidationErrors() as $errors) {
                foreach ($errors as $error) {
                    webAlertAndQuit($error, $userData['mode'], $id);
                    exit();
                }
            }
            exit();
        }
        // Save User Settings
        $userData['id'] = $user->getKey();
        \UserManager::saveSettings($userData);

        if (isset($userData['role'])
            && $userData['role'] != $user->attributes->role
            && EvolutionCMS()->hasPermission('save_role')) {
            \UserManager::setRole(['id' => $user->getKey(), 'role' => $userData['role']]);
        }

        if (isset($userData['user_groups']) && is_array($userData['user_groups'])) {
            \UserManager::setGroups(['id' => $user->getKey(), 'groups' => $userData['user_groups']]);
        }

        if ($userData['stay'] != '') {
            $a = ($userData['stay'] == '2') ? "88&id={$user->getKey()}" : "87";
            $this->parameters['url'] = "index.php?a={$a}&r=2&stay=" . $userData['stay'];
        } else {
            $this->parameters['url'] = "index.php?a=99&r=2";
        }
        if ($userData['passwordnotifymethod'] == 'e') {
            $websignupemail_message = EvolutionCMS()->getConfig('websignupemail_message');
            $site_url = EvolutionCMS()->getConfig('site_url');
            sendMailMessageForUser($user->attributes->email, $user->username, $userData['password'], $user->attributes->fullname, $websignupemail_message, $site_url);

        }
        if ($userData['passwordnotifymethod'] == 's' && $userData['newpassword'] == 1) {
            $this->parameters['username'] = $user->username;
            $this->parameters['password'] = $userData['password'];
            return true;
        }
        header("Location: " . $this->parameters['url']);
        exit();
    }
}
