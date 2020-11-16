<?php namespace EvolutionCMS\Services;

use EvolutionCMS\Models\SiteContent;
use \EvolutionCMS\Models\User;
use EvolutionCMS\Services\Documents\DocumentCreate;
use EvolutionCMS\Services\Documents\DocumentDelete;
use EvolutionCMS\Services\Documents\DocumentDuplicate;
use EvolutionCMS\Services\Documents\DocumentEdit;
use EvolutionCMS\Services\Documents\DocumentPublish;
use EvolutionCMS\Services\Documents\DocumentSetGroups;
use EvolutionCMS\Services\Documents\DocumentUndelete;
use EvolutionCMS\Services\Documents\DocumentUnpublish;
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
        $document = new DocumentCreate($userData, $events, $cache);
        return $document->process();
    }

    public function edit(array $userData, bool $events = true, bool $cache = true)
    {
        $document = new DocumentEdit($userData, $events, $cache);
        return $document->process();
    }

    public function duplicate(array $userData, bool $events = true, bool $cache = true)
    {
        $document = new DocumentDuplicate($userData, $events, $cache);
        return $document->process();
    }

    public function delete(array $userData, bool $events = true, bool $cache = true)
    {
        $username = new DocumentDelete($userData, $events, $cache);
        return $username->process();
    }

    public function undelete(array $userData, bool $events = true, bool $cache = true)
    {
        $username = new DocumentUndelete($userData, $events, $cache);
        return $username->process();
    }

    public function setGroups(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new DocumentSetGroups($userData, $events, $cache);
        return $user->process();
    }


    public function publish(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new DocumentPublish($userData, $events, $cache);
        return $user->process();
    }

    public function unpublished(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new DocumentUnpublish($userData, $events, $cache);
        return $user->process();
    }

    public function clearCart(array $userData = [], bool $events = true, bool $cache = true)
    {
        $user = new DocumentClear($userData, $events, $cache);
        return $user->process();
    }

}
