<?php namespace EvolutionCMS\Providers;

use AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver;
use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Database;

class DatabaseProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DBAPI', function ($app) {
            return new Database($app['config']->get('database.connections.default'), IlluminateDriver::class);
        });
    }
}
