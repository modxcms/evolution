<?php namespace EvolutionCMS\DocumentManager\Services;

use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentClearCart;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentCreate;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentDelete;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentDuplicate;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentEdit;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentPublish;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentSetGroups;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentUndelete;
use EvolutionCMS\DocumentManager\Services\Documents\DocumentUnpublish;

class DocumentManager
{

    public function get($id)
    {
        return SiteContent::withTrashed()->find($id);
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

    public function unpublish(array $userData, bool $events = true, bool $cache = true)
    {
        $user = new DocumentUnpublish($userData, $events, $cache);
        return $user->process();
    }

    public function clearCart(array $userData = [], bool $events = true, bool $cache = true)
    {
        $user = new DocumentClearCart($userData, $events, $cache);
        return $user->process();
    }

}
