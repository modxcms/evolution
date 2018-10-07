<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\Support\DocBlock;
use Illuminate\Support\ServiceProvider;

class DocBlockServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DocBlock', function () {
            return new DocBlock;
        });
    }
}
