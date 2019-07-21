<?php namespace EvolutionCMS\Facades;

use Illuminate\Support\Facades\Facade;

class TemplateProcessor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TemplateProcessor';
    }
}
