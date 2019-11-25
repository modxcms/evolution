<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\ManagerApi;

class ManagerApiServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ManagerAPI', function () {
            return new ManagerApi;
        });

        $this->app->setEvolutionProperty('ManagerAPI', 'manager');
    }
}
