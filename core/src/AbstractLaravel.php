<?php namespace EvolutionCMS;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;

abstract class AbstractLaravel extends Container implements ApplicationContract
{
    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    protected $coreAliases = [];

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The deferred services and their providers.
     *
     * @var array
     */
    protected $deferredServices = [];

    /**
     * The array of booting callbacks.
     *
     * @var array
     */
    protected $bootingCallbacks = [];
    /**
     * The array of booted callbacks.
     *
     * @var array
     */
    protected $bootedCallbacks = [];

    abstract public function getVersionData($data = null);
    abstract public function getConfig($name = '', $default = null);

    /**
     * @param array $classes
     */
    public function __construct()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance(Container::class, $this);

        $items = [];

        $this->instance('config', $config = new Repository($items));

        $this->loadConfiguration($config, EVO_CORE_PATH . 'config');
        $this->loadConfiguration($config, EVO_CORE_PATH . 'custom/config');

        if (null === $this['config']->get('app')) {
            throw new \Exception('Unable to load the "app" configuration file.');
        }

        date_default_timezone_set($config->get('app.timezone', 'UTC'));
        mb_internal_encoding('UTF-8');

        $this->register(new EventServiceProvider($this));
        //$this->register(new LogServiceProvider($this));
        //$this->register(new RoutingServiceProvider($this));

        $this->register(new \Illuminate\Filesystem\FilesystemServiceProvider($this));
        $this->register(new \Illuminate\Pagination\PaginationServiceProvider($this));
        $this->register(new \Illuminate\View\ViewServiceProvider($this));

        //$this->register(new \App\Providers\AppServiceProvider($this));
        //$this->register(new \App\Providers\EventServiceProvider($this));

        $this->registerCoreContainerAliases();

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this);
        AliasLoader::getInstance($this['config']->get('app.aliases'))->register();

        $this->registerConfiguredProviders();

        $this->boot();
    }

    protected function loadConfiguration($config, $dir)
    {
        $files = [];

        $configPath = realpath($dir);
        if ($configPath !== false) {
            /**
             * @var \SplFileInfo $file
             */
            foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
                $directory = $file->getPath();
                if ($directory = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
                    $directory = str_replace(DIRECTORY_SEPARATOR, '.', $directory) . '.';
                }
                $files[$directory . basename($file->getRealPath(), '.php')] = $file->getRealPath();
            }
            ksort($files, SORT_NATURAL);

            foreach ($files as $key => $path) {
                $config->set($key, require $path);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return $this->getVersionData('version');
    }

    /**
     * {@inheritdoc}
     */
    public function basePath()
    {
        return MODX_BASE_PATH;
    }

    /**
     * {@inheritdoc}
     */
    public function environment()
    {
        return 'production';
    }

    /**
     * {@inheritdoc}
     */
    public function runningInConsole()
    {
        return is_cli();
    }

    /**
     * {@inheritdoc}
     */
    public function runningUnitTests()
    {
        return $this->environment() === 'testing';
    }

    /**
     * {@inheritdoc}
     */
    public function isDownForMaintenance()
    {
        return (int)$this->getConfig('site_status', 0) === 0;
    }

    /**
     * Get the registered service provider instances if any exist.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return array
     */
    public function getProviders($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return Arr::where($this->serviceProviders, function ($value) use ($name) {
            return $value instanceof $name;
        });
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        return array_values($this->getProviders($provider))[0] ?? null;
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param  string  $provider
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProvider($provider)
    {
        return new $provider($this);
    }

    /**
     * {@inheritdoc}
     */
    public function registerConfiguredProviders()
    {
        $providers = Collection::make($this['config']->get('app.providers'))
            ->partition(function ($provider) {
                return Str::startsWith($provider, 'Illuminate\\');
            });
        (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))
            ->load($providers->collapse()->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function register($provider, $options = [], $force = false)
    {
        if (($registered = $this->getProvider($provider)) && ! $force) {
            return $registered;
        }
        // If the given "provider" is a string, we will resolve it, passing in the
        // application instance automatically for the developer. This is simply
        // a more convenient way of specifying your service provider classes.
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }
        if (method_exists($provider, 'register')) {
            $provider->register();
        }
        // If there are bindings / singletons set as properties on the provider we
        // will spin through them and register them with the application, which
        // serves as a convenience layer while registering a lot of bindings.
        if (property_exists($provider, 'bindings')) {
            foreach ($provider->bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }
        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $this->singleton($key, $value);
            }
        }
        $this->markAsRegistered($provider);
        // If the application has already booted, we will call this boot method on
        // the provider class so it has an opportunity to do its boot logic and
        // will be ready for any usage by this developer's application logic.
        if ($this->booted) {
            $this->bootProvider($provider);
        }
        return $provider;
    }

    /**
     * Mark the given provider as registered.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;
        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function registerDeferredProvider($provider, $service = null)
    {
        // Once the provider that provides the deferred service has been registered we
        // will remove it from our local list of the deferred services with related
        // providers so that this container does not try to resolve it out again.
        if ($service) {
            unset($this->deferredServices[$service]);
        }
        $this->register($instance = new $provider($this));
        if (! $this->booted) {
            $this->booting(function () use ($instance) {
                $this->bootProvider($instance);
            });
        }
    }

    /**
     * Boot the given service provider.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }
        // Once the application has booted we will also fire some "booted" callbacks
        // for any listeners that need to do work after this initial booting gets
        // finished. This is useful when ordering the boot-up processes we run.
        $this->fireAppCallbacks($this->bootingCallbacks);
        array_walk($this->serviceProviders, function ($provider) {
            $this->bootProvider($provider);
        });
        $this->booted = true;
        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    /**
     * {@inheritdoc}
     */
    public function booting($callback)
    {
        $this->bootingCallbacks[] = $callback;
    }
    /**
     * {@inheritdoc}
     */
    public function booted($callback)
    {
        $this->bootedCallbacks[] = $callback;
        if ($this->isBooted()) {
            $this->fireAppCallbacks([$callback]);
        }
    }
    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * Call the booting callbacks for the application.
     *
     * @param  array  $callbacks
     * @return void
     */
    protected function fireAppCallbacks(array $callbacks)
    {
        foreach ($callbacks as $callback) {
            call_user_func($callback, $this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath()
    {
        return MODX_BASE_PATH . 'assets/cache/services.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedPackagesPath()
    {
        return MODX_BASE_PATH . 'assets/cache/packages.php';
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        foreach ($this->coreAliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    public function getService($name)
    {
        return $this->get($name);
    }

    public function hasService($name)
    {
        return $this->has($name);
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        return $this->getService('DBAPI');
    }

    /**
     * @return Mail
     */
    public function getMail()
    {
        return $this->getService('MODxMailer');
    }

    /**
     * @return Legacy\PhpCompat
     */
    public function getPhpCompat()
    {
        return $this->getService('PHPCOMPAT');
    }

    /**
     * @return Legacy\PasswordHash
     */
    public function getPasswordHash()
    {
        return $this->getService('phpass');
    }

    /**
     * @return Support\MakeTable
     */
    public function getMakeTable()
    {
        return $this->getService('makeTable');
    }

    /**
     * @return Legacy\ExportSite
     */
    public function getExportSite()
    {
        return $this->getService('EXPORT_SITE');
    }

    /**
     * @return mixed
     */
    public function getDeprecatedCore()
    {
        return $this->getService('DEPRECATED');
    }

    /**
     * @return mixed
     */
    public function getManagerApi()
    {
        return $this->getService('ManagerAPI');
    }

    /**
     * @return mixed
     */
    public function getModifiers()
    {
        return $this->getService('MODIFIERS');
    }
}
