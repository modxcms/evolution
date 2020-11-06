<?php namespace EvolutionCMS\Tracy\Panels\Auth;

use Closure;
use Illuminate\Support\Arr;
use EvolutionCMS\Tracy\Panels\AbstractPanel;

class Panel extends AbstractPanel
{
    /**
     * getAttributes.
     *
     ** @return array
     */
    protected function getAttributes()
    {
        $attributes = [];
        if ($this->hasEvolutionCMS() === true) {
            $attributes = $this->getMgrUser();
        }
        return $this->identifier($attributes);
    }

    protected function getMgrUser() : array
    {
        $attributes = [];

        $id = $this->evolution->getLoginUserID('mgr');
        if ($id > 0) {
            $attributes = [
                'id' => $id,
                'rows' => $this->evolution->getUserInfo($id)
            ];
        }
        return $attributes;
    }


    /**
     * identifier.
     *
     * @param array $attributes
     * @return array
     */
    protected function identifier($attributes = [])
    {
        $id = Arr::get($attributes, 'id');
        $rows = Arr::get($attributes, 'rows', []);
        if (empty($rows) === true) {
            $id = 'Guest';
        } elseif (is_numeric($id) === true || empty($id) === true) {
            if (isset($rows['username'])) {
                if (isset($rows['usertype'])) {
                    $id = $rows['usertype'] . '['. $id . ']: ' . $rows['username'];
                } else {
                    $id .= ': ' . $rows['username'];
                }
            }
        }
        return [
            'id' => $id,
            'rows' => $rows,
        ];
    }
}
