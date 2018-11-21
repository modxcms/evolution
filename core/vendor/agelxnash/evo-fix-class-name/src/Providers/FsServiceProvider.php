<?php namespace AgelxNash\EvoFixClassName\Providers;

use Illuminate\Support\ServiceProvider;
use Helpers\FS;

class FsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('FS', function ($modx) {
            return FS::getInstance();
        });

        $this->app->setEvolutionProperty('FS', 'fs');
    }
}
