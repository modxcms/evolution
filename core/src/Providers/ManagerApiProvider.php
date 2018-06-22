<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\ManagerApi;

class ManagerApiProvider extends ServiceProvider
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
    }
}
