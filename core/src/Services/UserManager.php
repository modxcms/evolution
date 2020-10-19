<?php namespace EvolutionCMS\Services;

use \EvolutionCMS\Models\User;
use EvolutionCMS\Services\Users\UserRegistration;
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

    public function setGroups($userData)
    {

    }

    public function login($userData)
    {

    }

    public function logout($userData)
    {

    }

}
