<?php
if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @param bool $custom
     * @return string
     */
    function config_path($path = '', $custom = false)
    {
        $prefix = ($custom ? implode(['', '..', 'custom', 'config'], DIRECTORY_SEPARATOR) : '');
        return app()->make('path.config') . $prefix . ($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('storage_path'))
{
  /**
   * Get the path to the storage folder.
   *
   * @return  string
   */
  function storage_path($path = '')
  {
    return app('path.storage').($path ? '/'.$path : $path);
  }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return evolutionCMS()->basePath().($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $abstract
     * @param  array   $parameters
     * @return mixed|\EvolutionCMS\Core
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return evolutionCMS();
        }

        return evolutionCMS()->make($abstract, $parameters);
    }
}
