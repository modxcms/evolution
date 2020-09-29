<?php namespace AgelxNash\Modx\Evo\Database\Interfaces;

interface ConfigInterface
{
    /**
     * @param null|string $key
     * @return mixed
     */
    public function getConfig($key = null);

    /**
     * @param $data
     * @return $this
     */
    public function setConfig($data);
}
