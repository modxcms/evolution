<?php namespace EvolutionCMS\Providers;

use AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver;
use EvolutionCMS\PgSqlDatabase;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use EvolutionCMS\Database;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DBAPI', function ($app) {
            $capsule = new Capsule($app);
            $capsule->setAsGlobal();
            $capsule->setEventDispatcher($app['events']);
            switch ($app['config']->get('database.connections.default.driver')){
                case 'pgsql':
                     return new PgSqlDatabase(
                         $app['config']->get('database.connections.default', []),
                         IlluminateDriver::class
                     );
                    break;
                default:
                    return new Database(
                        $app['config']->get('database.connections.default', []),
                        IlluminateDriver::class
                    );
                    break;
            }

        });

        $this->app->setEvolutionProperty('DBAPI', 'db');
    }
}
