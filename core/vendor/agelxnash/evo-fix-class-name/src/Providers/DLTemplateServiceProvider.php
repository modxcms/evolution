<?php namespace AgelxNash\EvoFixClassName\Providers;

use Illuminate\Support\ServiceProvider;
use EvolutionCMS\Parser;

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
