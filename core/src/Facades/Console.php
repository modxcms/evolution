<?php namespace EvolutionCMS\Facades;

use Illuminate\Support\Facades\Facade;

class Console extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Console';
    }
}
