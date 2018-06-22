<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Database;
use AgelxNash\Modx\Evo\Database\Drivers\MySqliDriver;

class DatabaseProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('DBAPI', function () {
            global $database_server,
                   $database_type,
                   $dbase,
                   $database_user,
                   $database_password,
                   $table_prefix,
                   $database_connection_charset,
                   $database_connection_method,
                   $database_collation;

            return new Database(
                $database_server,
                $dbase,
                $database_user,
                $database_password,
                $table_prefix,
                $database_connection_charset,
                $database_connection_method,
                $database_collation,
                $database_type === 'mysqli' ? MySqliDriver::class : $database_type
            );
        });
    }
}
