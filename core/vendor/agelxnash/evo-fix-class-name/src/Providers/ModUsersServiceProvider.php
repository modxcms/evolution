<?php namespace AgelxNash\EvoFixClassName\Providers;

use Illuminate\Support\ServiceProvider;
use modUsers;

class ModUsersServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('modUsers', function ($modx) {
            return new modUsers($modx);
        });

        $this->app->setEvolutionProperty('modUsers', 'user');
    }
}
