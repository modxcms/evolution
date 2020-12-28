<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\Services\DocumentManager;
use Illuminate\Support\ServiceProvider;

class DocumentManagerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DocumentManager', function ($app) {
            return new DocumentManager($app);
        });
    }
}
