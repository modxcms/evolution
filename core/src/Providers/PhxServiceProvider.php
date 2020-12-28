<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS\Legacy\Phx;
use EvolutionCMS\AliasLoader;

class PhxServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Phx', function ($modx) {
            return new Phx($modx);
        });

        AliasLoader::getInstance()->alias('Phx', Phx::class);
        AliasLoader::getInstance()->alias('PHxParser', Phx::class);
        AliasLoader::getInstance()->alias('DLphx', Phx::class);
    }
}
