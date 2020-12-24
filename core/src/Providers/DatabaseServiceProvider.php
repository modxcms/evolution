<?php namespace EvolutionCMS\Providers;


use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Database;

class DatabaseServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('DBAPI', Database::class);

        $this->app->setEvolutionProperty('DBAPI', 'db');
    }
}
