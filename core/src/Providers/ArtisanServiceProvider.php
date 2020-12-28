<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\Console;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Cache\Console\ClearCommand as CacheClearCommand;
use Illuminate\Cache\Console\ForgetCommand as CacheForgetCommand;
use Illuminate\Database\Console\Migrations\FreshCommand as MigrateFreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand as MigrateResetCommand;
use Illuminate\Database\Console\Migrations\StatusCommand as MigrateStatusCommand;
use Illuminate\Database\Console\Migrations\InstallCommand as MigrateInstallCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand as MigrateRefreshCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand as MigrateRollbackCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

use EvolutionCMS\Console\ClearCompiledCommand;
use EvolutionCMS\Console\VendorPublishCommand;
use EvolutionCMS\Console\ViewClearCommand;
use EvolutionCMS\Console\Lists;
use EvolutionCMS\Console\Packages;

/** @see: https://github.com/laravel/framework/blob/5.6/src/Illuminate/Foundation/Providers/ArtisanServiceProvider.php */
class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear' => 'command.cache.clear',
        'CacheForget' => 'command.cache.forget',
        'ClearCompiled' => 'command.clear-compiled',
        'ClearCacheFull' => 'command.clear-cache-full',
        'Migrate' => 'command.migrate',
        'MigrateFresh' => 'command.migrate.fresh',
        'MigrateInstall' => 'command.migrate.install',
        'MigrateRefresh' => 'command.migrate.refresh',
        'MigrateReset' => 'command.migrate.reset',
        'MigrateRollback' => 'command.migrate.rollback',
        'MigrateStatus' => 'command.migrate.status',
        'Seed' => 'command.seed',
        'ViewClear' => 'command.view.clear',
        'ListsDoc' => 'command.lists.doc',
        'ListsTv' => 'command.lists.tv',
        'ListsTemplate' => 'command.lists.tpl',
        'Package' => 'command.packages.package',
        'PackageCreate' => 'command.packages.create',
        'RunPackageConsole' => 'command.packages.runconsole',
        'InstallPackageRequire' => 'command.packages.installrequire',
        'InstallPackageAutoload' => 'command.packages.installautoload',
        'UpdateTree' => 'command.updatetree',
        'SiteUpdate' => 'command.siteupdate',
        'Extras' => 'command.extras',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'VendorPublish' => 'command.vendor.publish',
        'MigrateMake' => 'command.migrate.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands(array_merge(
            $this->commands, $this->devCommands
        ));
        $this->app->singleton('Console', function ($app) {
            return new Console($app, $app['events'], '0.1');
        });
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCacheClearCommand()
    {
        $this->app->singleton('command.cache.clear', function ($app) {
            return new CacheClearCommand($app['cache'], $app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCacheForgetCommand()
    {
        $this->app->singleton('command.cache.forget', function ($app) {
            return new CacheForgetCommand($app['cache']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerClearCompiledCommand()
    {
        $this->app->singleton('command.clear-compiled', function () {
            return new ClearCompiledCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.migrate', function ($app) {
            return new MigrateCommand($app['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateFreshCommand()
    {
        $this->app->singleton('command.migrate.fresh', function () {
            return new MigrateFreshCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateInstallCommand()
    {
        $this->app->singleton('command.migrate.install', function ($app) {
            return new MigrateInstallCommand($app['migration.repository']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->singleton('command.migrate.refresh', function () {
            return new MigrateRefreshCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->singleton('command.migrate.reset', function ($app) {
            return new MigrateResetCommand($app['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton('command.migrate.rollback', function ($app) {
            return new MigrateRollbackCommand($app['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateStatusCommand()
    {
        $this->app->singleton('command.migrate.status', function ($app) {
            return new MigrateStatusCommand($app['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeedCommand()
    {
        $this->app->singleton('command.seed', function ($app) {
            return new SeedCommand($app['db']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerVendorPublishCommand()
    {
        $this->app->singleton('command.vendor.publish', function ($app) {
            return new VendorPublishCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerViewClearCommand()
    {
        $this->app->singleton('command.view.clear', function ($app) {
            return new ViewClearCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerListsDocCommand()
    {
        $this->app->singleton('command.lists.doc', function () {
            return new Lists\DocCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerListsTvCommand()
    {
        $this->app->singleton('command.lists.tv', function () {
            return new Lists\TvCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerListsTemplateCommand()
    {
        $this->app->singleton('command.lists.tpl', function () {
            return new Lists\TemplateCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerPackageCommand()
    {
        $this->app->singleton('command.packages.package', function () {
            return new Packages\PackageCommand();
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerPackageCreateCommand()
    {
        $this->app->singleton('command.packages.create', function () {
            return new Packages\PackageCreateCommand();
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRunPackageConsoleCommand()
    {
        $this->app->singleton('command.packages.runconsole', function () {
            return new Packages\RunPackageConsoleCommand();
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerInstallPackageRequireCommand()
    {
        $this->app->singleton('command.packages.installrequire', function () {
            return new Packages\InstallPackageRequireCommand();
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerInstallPackageAutoloadCommand()
    {
        $this->app->singleton('command.packages.installautoload', function () {
            return new Packages\InstallPackageAutoloadCommand();
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerUpdateTreeCommand()
    {
        $this->app->singleton('command.updatetree', function () {
            return new Console\UpdateTreeCommand();
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSiteUpdateCommand()
    {
        $this->app->singleton('command.siteupdate', function () {
            return new Console\SiteUpdateCommand();
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerExtrasCommand()
    {
        $this->app->singleton('command.extras', function () {
            return new Packages\ExtrasCommand();
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerClearCacheFullCommand()
    {
        $this->app->singleton('command.clear-cache-full', function () {
            return new Console\ClearCacheFullCommand();
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];

            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(array_values($this->commands), array_values($this->devCommands));
    }


}
