<?php namespace EvolutionCMS\UserManager\Facades;

use Illuminate\Support\Facades\Facade;

class UserManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'UserManager';
    }
}
