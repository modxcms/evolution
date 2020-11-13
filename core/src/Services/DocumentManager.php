<?php namespace EvolutionCMS\Services;

use EvolutionCMS\Models\SiteContent;
use \EvolutionCMS\Models\User;
use EvolutionCMS\Services\Users\UserGeneratePassword;
use EvolutionCMS\Services\Users\UserGetVerifiedKey;
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
use EvolutionCMS\Services\Users\UserVerified;

class DocumentManager
{

    public function get($id)
    {
        return SiteContent::find($id);
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

    public function setGroups(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserSetGroups($userData, $events, $cache);
        return $user->process();
    }


    public function publish(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new UserSetGroups($userData, $events, $cache);
        return $user->process();
    }

    public function clearCart(array $userData = [], bool $events = true, bool $cache = true)
    {
        $user = new UserSetGroups($userData, $events, $cache);
        return $user->process();
    }

}
