<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\TemplateProcessor;
use Illuminate\Support\ServiceProvider;

class TemplateProcessorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('TemplateProcessor', function ($app) {
            return new TemplateProcessor($app);
        });
    }
}
