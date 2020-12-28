<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\ExceptionHandler;

class ExceptionHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ExceptionHandler', function ($app) {
            return new ExceptionHandler($app);
        });
    }
}
