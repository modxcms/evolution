<?php
if (! function_exists('var_debug')) {
    /**
     * Dumps information about a variable in Tracy Debug Bar.
     * @tracySkipLocation
     * @param  mixed $var
     * @param  string $title
     * @param  array $options
     * @return mixed  variable itself
     */
    function var_debug($var, $title = null, array $options = null)
    {
        return EvolutionCMS\Tracy\Debugger::barDump($var, $title, $options);
    }
}
