<?php namespace EvolutionCMS\Exceptions;

use Exception;

class ContainerException extends Exception{
    protected $service;

    public function setService($name)
    {
        $this->service = $name;

        return $this;
    }

    public function getService()
    {
        return $this->service;
    }
}
