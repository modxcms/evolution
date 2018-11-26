<?php namespace EvolutionCMS\Providers;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS\Parser;
use EvolutionCMS\AliasLoader;

class DLTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * app('DLTemplate')
     * \DLTemplate::getInstance($modx)
     * \EvolutionCMS\Parser::getInstance($modx);
     * $modx->tpl
     *
     * @return void
     */
    public function register()
    {
        AliasLoader::getInstance()->alias('DLTemplate', Parser::class);

        $this->app->bind('DLTemplate', function ($modx) {
            /**
             * Hack :-)
             * Don't use Parser. We need load DLTemplate alias
             */
            return \DLTemplate::getInstance($modx);
        });

        $this->app->setEvolutionProperty('DLTemplate', 'tpl');
    }
}
