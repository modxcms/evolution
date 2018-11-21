<?php namespace AgelxNash\EvoFixClassName\Providers;

use Illuminate\Support\ServiceProvider;
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
