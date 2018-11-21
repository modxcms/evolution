<?php namespace AgelxNash\EvoFixClassName\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Phx;

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
    }
}
