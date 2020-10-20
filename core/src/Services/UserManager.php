<?php namespace EvolutionCMS\Services;

use \EvolutionCMS\Models\User;
use EvolutionCMS\Services\Users\UserDelete;
use EvolutionCMS\Services\Users\UserLogin;
use EvolutionCMS\Services\Users\UserRegistration;
use EvolutionCMS\Services\Users\UserSetGroups;
use EvolutionCMS\Services\Users\UserSetRole;

class UserManager
{

    public function get($id)
    {
        return User::find($id);
    }

    public function create(array $userData, $events = true, $cache = true)
    {
        $registration = new UserRegistration($userData, $events, $cache);
        return $registration->process();
    }

    public function edit(array $userData, $events = true, $cache = true)
    {

    }

    public function delete(array $userData, $events = true, $cache = true)
    {
        $username = new UserDelete($userData, $events, $cache);
        return $username->process();
    }

    public function dropPassword($userData, $events = true, $cache = true)
    {

    }

    public function changePassword($userData, $events = true, $cache = true)
    {

    }

    public function setRole(array $userData, $events = true, $cache = true)
    {
        $user = new UserSetRole($userData, $events, $cache);
        return $user->process();
    }

    public function setGroups(array $userData, $events = true, $cache = true)
    {
        $user = new UserSetGroups($userData, $events, $cache);
        return $user->process();
    }

    public function login(array $userData, $events = true, $cache = true)
    {
        $user = new UserLogin($userData, $events, $cache);
        return $user->process();
    }

    public function logout()
    {

    }

}
