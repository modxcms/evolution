<?php namespace AgelxNash\Modx\Evo\Database\Interfaces;

use AgelxNash\Modx\Evo\Database\Exceptions;

interface ProxyInterface extends ConfigInterface
{
    /**
     * @return string|null
     * @throws Exceptions\ConnectException
     */
    public function getLastError();

    /**
     * @return string|null
     * @throws Exceptions\ConnectException
     */
    public function getLastErrorNo();

    /**
     * @return mixed
     * @throws Exceptions\ConnectException
     */
    public function connect();

    /**
     * @return bool
     */
    public function disconnect();

    /**
     * @param $result
     * @return bool
     */
    public function isResult($result);

    /**
     * @param $result
     * @return int
     */
    public function numFields($result);

    /**
     * @param $result
     * @param int $col
     * @return null|string
     */
    public function fieldName($result, $col = 0);

    /**
     * @param string $charset
     * @param null|string $method
     * @return bool
     * @throws Exceptions\Exception
     */
    public function setCharset($charset, $method = null);

    /**
     * @param string $name
     * @return bool
     * @throws Exceptions\Exception
     */
    public function selectDb($name);

    /**
     * @param $data
     * @return mixed
     * @throws Exceptions\Exception
     */
    public function escape($data);

    /**
     * @param string $sql
     * @return mixed
     * @throws Exceptions\Exception
     */
    public function query($sql);

    /**
     * @param mixed $result
     * @return int
     */
    public function getRecordCount($result);

    /**
     * @param $result
     * @param string $mode
     * @return mixed
     * @throws Exceptions\Exception
     */
    public function getRow($result, $mode = 'assoc');

    /**
     * @return string
     * @throws Exceptions\Exception
     */
    public function getVersion();

    /**
     * @return mixed
     * @throws Exceptions\Exception
     */
    public function getInsertId();

    /**
     * @return int
     * @throws Exceptions\Exception
     */
    public function getAffectedRows();

    /**
     * @param string $name
     * @param $result
     * @return array
     */
    public function getColumn($name, $result);

    /**
     * @param $result
     * @return array
     */
    public function getColumnNames($result);

    /**
     * @param $result
     * @return mixed
     */
    public function getValue($result);

    /**
     * @param $result
     * @return mixed
     */
    public function getTableMetaData($result);

    /**
     * @param int $flag
     * @param string $name
     * @return bool
     */
    public function begin($flag = 0, $name = null);

    /**
     * @param int $flag
     * @param string $name
     * @return bool
     */
    public function commit($flag = 0, $name = null);

    /**
     * @param int $flag
     * @param string $name
     * @return bool
     */
    public function rollback($flag = 0, $name = null);
}
