<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Mail;

class MailProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MODxMailer', function () {
            return new Mail;
        });
    }
}
