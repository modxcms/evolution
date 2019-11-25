<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\ServiceProvider;
use modResource;

class ModResourceServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('modResource', function ($modx) {
            return new modResource($modx);
        });

        $this->app->setEvolutionProperty('modResource', 'doc');
    }
}
