<?php namespace EvolutionCMS\Services;

use \EvolutionCMS\Models\User;
use EvolutionCMS\Services\Users\UserRegistration;

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

    public function setRole($userData)
    {

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
