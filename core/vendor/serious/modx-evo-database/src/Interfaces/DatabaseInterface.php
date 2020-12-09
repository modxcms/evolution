<?php namespace AgelxNash\Modx\Evo\Database\Interfaces;

use AgelxNash\Modx\Evo\Database\Exceptions;

interface DatabaseInterface extends ProxyInterface
{
    /**
     * @return mixed
     */
    public function getDriver();

    /**
     * @param string|DriverInterface $driver
     * @return DriverInterface
     * @throws Exceptions\DriverException
     */
    public function setDriver($driver);

    /**
     * @param mixed $result
     * @param bool $index
     * @return array
     * @throws Exceptions\UnknownFetchTypeException
     */
    public function makeArray($result, $index = false);

    /**
     * @param string $table
     * @param string|array $where
     * @param string $orderBy
     * @param string $limit
     * @return mixed
     * @throws Exceptions\TableNotDefinedException
     * @throws Exceptions\InvalidFieldException
     */
    public function delete($table, $where = '', $orderBy = '', $limit = '');

    /**
     * @param string|array $fields
     * @param string|array $tables
     * @param string|array $where
     * @param string $orderBy
     * @param string $limit
     * @return mixed
     * @throws Exceptions\TableNotDefinedException
     * @throws Exceptions\InvalidFieldException
     */
    public function select($fields, $tables, $where = '', $orderBy = '', $limit = '');

    /**
     * @param string|array $values
     * @param string $table
     * @param string|array $where
     * @return mixed
     * @throws Exceptions\TableNotDefinedException
     * @throws Exceptions\InvalidFieldException
     */
    public function update($values, $table, $where = '');

    /**
     * @param string|array $fields
     * @param string $table
     * @param string|array $fromFields
     * @param string $fromTable
     * @param string|array $where
     * @param string $limit
     * @return mixed
     * @throws Exceptions\TableNotDefinedException
     * @throws Exceptions\InvalidFieldException
     * @throws Exceptions\GetDataException
     * @throws Exceptions\TooManyLoopsException
     */
    public function insert(
        $fields,
        $table,
        $fromFields = '*',
        $fromTable = '',
        $where = '',
        $limit = ''
    );

    /**
     * @param string|array $fields
     * @param string $table
     * @param string|array $where
     * @return mixed
     * @throws Exceptions\TableNotDefinedException
     * @throws Exceptions\InvalidFieldException
     * @throws Exceptions\GetDataException
     * @throws Exceptions\TooManyLoopsException
     */
    public function save($fields, $table, $where = '');

    /**
     * @param string $table
     * @return bool
     */
    public function optimize($table);

    /**
     * @param string $table
     * @return bool
     */
    public function alterTable($table);

    /**
     * @param string $table
     * @return bool
     */
    public function truncate($table);

    /**
     * @param string $table
     * @param bool $escape
     * @return string
     * @throws Exceptions\TableNotDefinedException
     */
    public function getTableName($table, $escape = true);

    /**
     * @param string $table
     * @return string
     * @throws Exceptions\TableNotDefinedException
     */
    public function getFullTableName($table);
}
