<?php namespace EvolutionCMS\Traits;

use Carbon\Carbon;

trait Helpers
{
    abstract public function getConfig($name = '', $default = null);

    function timestamp() : int
    {
        return time() + (int)$this->getConfig('server_offset_time', 0);
    }

    /**
     * @return Carbon
     */
    function now() : Carbon
    {
        return Carbon::now()->addSeconds(
            evolutionCMS()->getConfig('server_offset_time', 0)
        );
    }
}
