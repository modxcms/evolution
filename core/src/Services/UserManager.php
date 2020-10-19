<?php namespace EvolutionCMS\Services;

use \EvolutionCMS\Models\User;
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

    public function create(array $userData)
    {
        $registration = new UserRegistration($userData);
        return $registration->process();
    }

    public function edit($id, $userData)
    {

    }

    public function delete($id)
    {

    }

    public function dropPassword($userData)
    {

    }

    public function changePassword($userData)
    {

    }

    public function setRole(int $id, int $role = 0)
    {
        $user = new UserSetRole(['id' => $id, 'role' => $role]);
        return $user->process();
    }

    public function setGroups(int $id, array $groups = [])
    {
        $user = new UserSetGroups(['id' => $id, 'groups' => $groups]);
        return $user->process();
    }

    public function login(string $login, string $password, int $remember = 0, string $captcha = '')
    {
        $user = new UserLogin(['username' => $login, 'password' => $password, 'remember' => $remember, 'captcha' => $captcha]);
        return $user->process();
    }

    public function logout($userData)
    {

    }

}
