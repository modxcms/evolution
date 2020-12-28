<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\HelperProcessor;
use Illuminate\Support\ServiceProvider;

class HelperProcessorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('HelperProcessor', function ($app) {
            return new HelperProcessor($app);
        });
    }
}
