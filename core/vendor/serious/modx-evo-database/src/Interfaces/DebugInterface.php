<?php namespace AgelxNash\Modx\Evo\Database\Interfaces;

interface DebugInterface
{
    /**
     * @return bool
     */
    public function isDebug();

    /**
     * @param bool $flag
     * @return DebugInterface
     */
    public function setDebug($flag);

    /**
     * @param null|string $query
     * @return bool
     */
    public function checkLastError($query = null);

    /**
     * @param mixed $result
     * @param string $sql
     * @param int $time
     * @return array
     */
    public function collectQuery($result, $sql, $time);

    /**
     * @param string $query
     * @return string
     */
    public function setLastQuery($query);

    /**
     * @return string
     */
    public function getLastQuery();

    /**
     * @return array
     */
    public function getAllExecutedQuery();

    /**
     * @return bool
     */
    public function flushExecutedQuery();

    /**
     * @param int $value
     * @return int
     */
    public function setConnectionTime($value);

    /**
     * @param bool $format
     * @return string|float
     */
    public function getConnectionTime($format = false);

    /**
     * @return int
     */
    public function getQueriesTime();

    /**
     * @param int $time
     * @return int
     */
    public function addQueriesTime($time);

    /**
     * @return string|null
     */
    public function getLastError();

    /**
     * @return string|null
     */
    public function getLastErrorNo();
}
