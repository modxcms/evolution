<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\Services\AuthServices;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AuthServices', function ($app) {
            return new AuthServices($app);
        });
    }
}
