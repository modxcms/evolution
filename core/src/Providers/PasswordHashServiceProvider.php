<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\PasswordHash;

class PasswordHashServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('phpass', function () {
            return new PasswordHash;
        });

        $this->app->setEvolutionProperty('phpass', 'phpass');
    }
}
