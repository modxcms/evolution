<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\UrlProcessor;
use Illuminate\Support\ServiceProvider;

class UrlProcessorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('UrlProcessor', function ($app) {
            return new UrlProcessor($app);
        });
    }
}
