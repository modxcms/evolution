<?php namespace AgelxNash\Modx\Evo\Database;

class Database extends AbstractDatabase
{
    /**
     * @param array $config
     * @param string $driver
     * @throws Exceptions\Exception
     */
    public function __construct(array $config, $driver = Drivers\MySqliDriver::class)
    {
        $this->setConfig($config);
        $this->setDriver($driver);
    }
}
