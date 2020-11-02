<?php namespace EvolutionCMS\Services;

use \EvolutionCMS\Models\User;
use EvolutionCMS\Services\Users\UserHashChangePassword;
use EvolutionCMS\Services\Users\UserManagerChangePassword;
use EvolutionCMS\Services\Users\UserChangePassword;
use EvolutionCMS\Services\Users\UserDelete;
use EvolutionCMS\Services\Users\UserEdit;
use EvolutionCMS\Services\Users\UserHashLogin;
use EvolutionCMS\Services\Users\UserLogin;
use EvolutionCMS\Services\Users\UserLogout;
use EvolutionCMS\Services\Users\UserRefreshToken;
use EvolutionCMS\Services\Users\UserRegistration;
use EvolutionCMS\Services\Users\UserRepairPassword;
use EvolutionCMS\Services\Users\UserSaveSettings;
use EvolutionCMS\Services\Users\UserSetGroups;
use EvolutionCMS\Services\Users\UserSetRole;

class UserManager
{

    public function get($id)
    {
        return User::find($id);
    }

    public function create(array $userData, bool $events = true, bool $cache = true)
    {
        $registration = new UserRegistration($userData, $events, $cache);
        return $registration->process();
    }

    public function edit(array $userData, bool $events = true, bool $cache = true)
    {
        $userEdit = new UserEdit($userData, $events, $cache);
        return $userEdit->process();
    }

    public function delete(array $userData, bool $events = true, bool $cache = true)
    {
        $username = new UserDelete($userData, $events, $cache);
        return $username->process();
    }

    public function repairPassword($userData, bool $events = true, bool $cache = true)
    {
        $userHash = new UserRepairPassword($userData, $events, $cache);
        return $userHash->process();
    }

    public function changeManagerPassword($userData, bool $events = true, bool $cache = true)
    {
        $user = new UserManagerChangePassword($userData, $events, $cache);
        return $user->process();
    }

    public function changePassword($userData, bool $events = true, bool $cache = true)
    {
        $user = new UserChangePassword($userData, $events, $cache);
        return $user->process();
    }

    public function hashChangePassword($userData, bool $events = true, bool $cache = true)
    {
        $user = new UserHashChangePassword($userData, $events, $cache);
        return $user->process();
    }

    public function setRole(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserSetRole($userData, $events, $cache);
        return $user->process();
    }

    public function setGroups(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserSetGroups($userData, $events, $cache);
        return $user->process();
    }

    public function login(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserLogin($userData, $events, $cache);
        return $user->process();
    }

    public function hashLogin(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserHashLogin($userData, $events, $cache);
        return $user->process();
    }

    public function logout(array $userData = [], bool $events = true, bool $cache = true)
    {
        $user = new UserLogout($userData, $events, $cache);
        return $user->process();
    }

    public function saveSettings(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserSaveSettings($userData, $events, $cache);
        return $user->process();
    }

    public function refreshToken(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserRefreshToken($userData, $events, $cache);
        return $user->process();
    }

}
