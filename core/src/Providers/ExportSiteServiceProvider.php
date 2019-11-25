<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\ExportSite;

class ExportSiteServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('EXPORT_SITE', function () {
            return new ExportSite;
        });

        $this->app->setEvolutionProperty('EXPORT_SITE', 'export');
    }
}
