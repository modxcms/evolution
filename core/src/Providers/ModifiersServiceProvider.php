<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\Modifiers;

class ModifiersServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MODIFIERS', function () {
            return new Modifiers;
        });

        $this->app->setEvolutionProperty('MODIFIERS', 'filter');
    }
}
