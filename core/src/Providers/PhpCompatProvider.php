<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Legacy\PhpCompat;

class PhpCompatProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('PHPCOMPAT', function () {
            return new PhpCompat;
        });
    }
}
