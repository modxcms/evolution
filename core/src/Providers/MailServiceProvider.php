<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Mail;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MODxMailer', function ($modx) {
            return (new Mail)->init($modx);
        });

        $this->app->setEvolutionProperty('MODxMailer', 'mail');
    }
}
