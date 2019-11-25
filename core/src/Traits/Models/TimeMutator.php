<?php namespace EvolutionCMS\Traits\Models;

use Illuminate\Support\Carbon;

trait TimeMutator
{
    protected function convertTimestamp($value) :? Carbon
    {
        if (empty($value)) {
            return null;
        }

        $out = $this->asDateTime($value)
            ->addSeconds(evolutionCMS()->getConfig('server_offset_time'));

        $out::setToStringFormat(evolutionCMS()->normalizeFormat());

        return $out;
    }
}
