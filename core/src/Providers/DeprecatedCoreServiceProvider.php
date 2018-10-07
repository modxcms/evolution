<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\DeprecatedCore;

class DeprecatedCoreServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DEPRECATED', function () {
            return new DeprecatedCore();
        });
    }
}
