<?php

namespace EvolutionCMS\Providers;

use EvolutionCMS\Extensions\Router;
use Illuminate\Routing\RoutingServiceProvider as IlluminateRoutingServiceProvider;

class RoutingServiceProvider extends IlluminateRoutingServiceProvider
{
    /**
     * We need to overload provider to register our router
     * to use custom route methods
     */
    protected function registerRouter()
    {
        $this->app->singleton('router', function ($app) {
            return new Router($app['events'], $app);
        });
    }
}
