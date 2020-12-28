<?php namespace EvolutionCMS\Traits\Models;

trait ManagerActions
{
    public function getManagerActionsMap() : array
    {
        return property_exists($this, 'managerActionsMap') ? $this->managerActionsMap : [];
    }

    public function makeUrl($type, bool $full = false) :string
    {
        $map = $this->getManagerActionsMap();
        switch (true) {
            case isset($map[$type]) && is_scalar($map[$type]):
                $out = '?' . http_build_query([
                        'a' => $map[$type]
                    ]);
                break;
            case $this->exists && isset($map['id'][$type]) &&
                is_scalar($map['id'][$type]):
                $out = '?' . http_build_query([
                        'a' => $map['id'][$type],
                        'id' => $this->getKey()
                    ]);
                break;
            default:
                $out = '#';
        }

        return ($full ? MODX_MANAGER_URL : '') . $out;
    }
}
