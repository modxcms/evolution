<?php namespace EvolutionCMS;

use Illuminate\Console\Application as Artisan;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Console\Events;
use Symfony\Component\Console\Application as SymfonyApplication;

class Console extends Artisan
{
    /**
     * {@inheritDoc}
     */
    public function __construct(Container $laravel, Dispatcher $events, $version)
    {
        SymfonyApplication::__construct($laravel->getVersionData('branch'), $version);
        $this->laravel = $laravel;
        $this->events = $events;
        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        $this->events->dispatch(new Events\ArtisanStarting($this));

        $laravel->loadDeferredProviders();

        parent::bootstrap();
    }

    /**
     * @{inheritDoc}
     */
    protected function getDefaultInputDefinition()
    {
        return SymfonyApplication::getDefaultInputDefinition();
    }
}
