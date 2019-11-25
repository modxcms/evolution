<?php namespace EvolutionCMS\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BladeServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $directives = $this->app['config']->get('view.directive');
        if (\is_array($directives)) {
            foreach ($directives as $name => $callback) {
                $this->app->get('blade.compiler')->directive($name, $callback);
            }
        }
    }
}
