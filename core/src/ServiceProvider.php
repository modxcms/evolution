<?php namespace EvolutionCMS;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @property Core $app
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register a view file namespace.
     *
     * @param  string|array  $path
     * @param  string  $namespace
     * @return void
     */
    protected function loadViewsFrom($path, $namespace)
    {
        if (\is_array($this->app['config']->get('view.paths'))) {
            foreach ($this->app['config']->get('view.paths') as $viewPath) {
                if (is_dir($appPath = $viewPath.'/vendor/'.$namespace)) {
                    $this->app['view']->addNamespace($namespace, $appPath);
                }
            }
        }

        $this->app['view']->addNamespace($namespace, $path);
    }

    protected function loadSnippetsFrom($path, $namespace)
    {
        $found = $this->app->findElements('snippet', $path, array('php'));
        foreach ($found as $name => $code) {
            $this->addSnippet($name, $code, $namespace);
        }
    }

    protected function loadChunksFrom($path, $namespace)
    {
        $found = $this->app->findElements('chunk', $path, array('tpl', 'html'));
        foreach ($found as $name => $code) {
            $this->addChunk($name, $code, $namespace);
        }
    }

    protected function addSnippet($name, $code, $namespace)
    {
        $this->app->addSnippet($name, $code, $namespace . '#');
    }

    protected function addChunk($name, $code, $namespace)
    {
        $this->app->addChunk($name, $code, $namespace . '#');
    }
}
