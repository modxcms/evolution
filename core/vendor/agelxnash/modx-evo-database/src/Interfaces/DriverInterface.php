<?php namespace AgelxNash\Modx\Evo\Database\Interfaces;

use AgelxNash\Modx\Evo\Database\Exceptions;

interface DriverInterface extends ProxyInterface
{
    /**
     * @param array $config
     * @throws Exceptions\ConnectException
     */
    public function __construct(array $config = []);

    /**
     * @return mixed
     * @throws Exceptions\ConnectException
     */
    public function getConnect();

    /**
     * @return bool
     */
    public function isConnected();
}
