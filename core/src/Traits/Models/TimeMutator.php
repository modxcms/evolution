<?php namespace EvolutionCMS\Traits\Models;

trait TimeMutator
{
    protected function convertTimestamp($value)
    {
        if (empty($value)) {
            return null;
        }
        $out = $this->asDateTime($value)
            ->addSeconds(evolutionCMS()->getConfig('server_offset_time', 0));

        $out::setToStringFormat(evolutionCMS()->normalizeFormat());

        return $out;
    }
}
