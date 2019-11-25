<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Support\MakeTable;

class MakeTableServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('makeTable', function () {
            return new MakeTable;
        });

        $this->app->setEvolutionProperty('makeTable', 'table');
    }
}
