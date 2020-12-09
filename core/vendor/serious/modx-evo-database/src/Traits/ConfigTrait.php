<?php namespace AgelxNash\Modx\Evo\Database\Traits;

trait ConfigTrait
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * {@inheritDoc}
     */
    public function getConfig($key = null)
    {
        return ($key === null ? $this->config : (isset($this->config[$key]) ? $this->config[$key] : null));
    }

    /**
     * {@inheritDoc}
     */
    public function setConfig($data)
    {
        $this->config = $data;

        return $this;
    }
}
