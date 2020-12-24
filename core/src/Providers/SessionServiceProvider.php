<?php

namespace EvolutionCMS\Providers;

use Illuminate\Session\SessionServiceProvider as IlluminateSessionServiceProvider;
use Illuminate\Session\SessionManager;

class SessionServiceProvider extends IlluminateSessionServiceProvider
{
    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        parent::registerSessionManager();
        $this->app->alias('session', SessionManager::class);
    }
}
