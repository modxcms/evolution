<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS\Parser;
use EvolutionCMS\AliasLoader;

class DLTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('DLTemplate', function ($modx) {
            return Parser::getInstance($modx);
        });

        $this->app->setEvolutionProperty('DLTemplate', 'tpl');

        AliasLoader::getInstance()->alias('DLTemplate', Parser::class);
    }
}
