<?php namespace AgelxNash\Modx\Evo\Database\Drivers;

use AgelxNash\Modx\Evo\Database\Exceptions;
use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use mysqli_driver;
use ReflectionClass;

/**
 * @property mysqli $conn
 */
class MySqliDriver extends AbstractDriver
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $config = [])
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;

        $this->setConfig($config);
    }

    /**
     * {@inheritDoc}
     * @return mysqli
     */
    public function getConnect()
    {
        if (! $this->isConnected()) {
            return $this->connect();
        }

        return $this->conn;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return ($this->conn instanceof mysqli);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastError()
    {
        return $this->getConnect()->error;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastErrorNo()
    {
        $out = (string)$this->getConnect()->sqlstate;

        if ($out === '00000') {
            $out = '';
        }

        return $out;
    }

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        try {
            $this->conn = new mysqli(
                $this->getConfig('host'),
                $this->getConfig('username'),
                $this->getConfig('password'),
                $this->getConfig('database')
            );
        } catch (mysqli_sql_exception $exception) {
            $this->conn = null;
            throw new Exceptions\ConnectException($exception->getMessage(), $exception->getCode());
        }

        return $this->conn;
    }

    /**
     * {@inheritDoc}
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->conn->close();
        }

        $this->conn = null;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isResult($result)
    {
        return $result instanceof mysqli_result;
    }

    /**
     * @param mysqli_result $result
     * {@inheritDoc}
     */
    public function numFields($result)
    {
        return $this->isResult($result) ? $result->field_count : 0;
    }

    /**
     * @param mysqli_result $result
     * {@inheritDoc}
     */
    public function fieldName($result, $col = 0)
    {
        $field = $this->isResult($result) ? $result->fetch_field_direct($col) : [];

        return isset($field->name) ? $field->name : null;
    }

    /**
     * {@inheritDoc}
     * @throws \ReflectionException
     */
    public function setCharset($charset, $method = null)
    {
        if ($method === null) {
            $method = $this->getConfig('method');
        }

        $this->query($method . ' ' . $charset);

        return $this->getConnect()->set_charset($charset);
    }

    /**
     * {@inheritDoc}
     */
    public function selectDb($name)
    {
        return $this->getConnect()->select_db($name);
    }

    /**
     * {@inheritDoc}
     */
    public function escape($data)
    {
        return $this->getConnect()->escape_string($data);
    }

    /**
     * {@inheritDoc}
     * @return bool|mysqli_result
     * @throws \ReflectionException
     */
    public function query($sql)
    {
        try {
            $result = $this->getConnect()->query($sql);
        } catch (mysqli_sql_exception $exception) {
            $reflect = new ReflectionClass($exception);
            $property = $reflect->getProperty('sqlstate');
            $property->setAccessible(true);
            throw (new Exceptions\QueryException($exception->getMessage(), $property->getValue($exception)))
                ->setQuery($sql);
        }

        return $result;
    }

    /**
     * @param mysqli_result $result
     * {@inheritDoc}
     */
    public function getRecordCount($result)
    {
        return $this->isResult($result) ? $result->num_rows : 0;
    }

    /**
     * @param mysqli_result $result
     * {@inheritDoc}
     */
    public function getRow($result, $mode = 'assoc')
    {
        switch ($mode) {
            case 'assoc':
                $out = $result->fetch_assoc();
                break;
            case 'num':
                $out = $result->fetch_row();
                break;
            case 'object':
                $out = $result->fetch_object();
                break;
            case 'both':
                $out = $result->fetch_array(MYSQLI_BOTH);
                break;
            default:
                throw new Exceptions\UnknownFetchTypeException(
                    "Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'."
                );
        }

        return $out;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {
        return $this->getConnect()->server_info;
    }

    /**
     * {@inheritDoc}
     */
    public function getInsertId()
    {
        return $this->getConnect()->insert_id;
    }

    /**
     * {@inheritDoc}
     */
    public function getAffectedRows()
    {
        return $this->getConnect()->affected_rows;
    }

    /**
     * @param mysqli_result $result
     * @param int $position
     * @return bool
     */
    public function dataSeek(&$result, $position)
    {
        return $this->isResult($result) ? $result->data_seek($position) : false;
    }

    /**
     * {@inheritDoc}
     */
    public function begin ($flag = 0, $name = null)
    {
        return $this->getConnect()->begin_transaction($flag, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function commit ($flag = 0, $name = null)
    {
        return $this->getConnect()->commit($flag, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function rollback ($flag = 0, $name = null)
    {
        return $this->getConnect()->rollback($flag, $name);
    }
}
