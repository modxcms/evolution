<?php namespace EvolutionCMS;


use Exception;

//use AgelxNash\Modx\Evo\Database\Exceptions;
//use AgelxNash\Modx\Evo\Database\Drivers;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use PDOStatement;

class Database extends Manager
{

    /**
     * @var int
     */
    protected $affectedRows = 0;

    /**
     * @var int
     */
    protected $safeLoopCount = 1000;

    protected $driver;


    /**
     * @param $tableName
     * @param bool $force
     * @return null|string|string[]
     * @throws Exceptions\TableNotDefinedException
     */
    public function replaceFullTableName($tableName, $force = false)
    {
        $tableName = trim($tableName);
        if ((bool)$force === true) {
            $result = $this->getConnection()->getTablePrefix() . $tableName;
        } elseif (strpos($tableName, '[+prefix+]') !== false) {
            $dbase = trim($this->getConfig('database'), '`');
            $prefix = $this->getConfig('prefix');

            $result = preg_replace(
                '@\[\+prefix\+\](\w+)@',
                '`' . $dbase . '`.`' . $prefix . '$1`',
                $tableName
            );
        } else {
            $result = $tableName;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function query($sql, $watchError = true)
    {
        try {
            $pdo = \DB::connection()->getPdo();
            $out = [];
            if (\is_array($sql)) {
                foreach ($sql as $query) {
                    $out[] = $pdo->prepare($this->replaceFullTableName($query));
                }
            } else {
                $out = $pdo->prepare($this->replaceFullTableName($sql));
            }

            return $out;
        } catch (Exception $exception) {
            if ($watchError === true) {
                evolutionCMS()->getService('ExceptionHandler')->messageQuit($exception->getMessage());
            }
        }
    }


    /**
     * {@inheritDoc}
     * @return bool|PDOStatement
     */
    public function _query($sql)
    {
        try {
            $start = microtime(true);

            $result = $this->prepare($sql);
            $this->execute($result);

            if ($this->saveAffectedRows($result) === 0 && $this->isResult($result) && !$this->isSelectQuery($sql)) {
                $result = true;
            }
            $this->getConnect()->logQuery($sql, [], $this->getElapsedTime($start));
        } catch (\Exception $exception) {
            $this->lastError = $this->isResult($result) ? $result->errorInfo() : [];
            $code = $this->isResult($result) ? $result->errorCode() : '';
            $this->lastErrorNo = $this->isResult($result) ? (empty($code) ? $exception->getCode() : $code) : '';
            throw (new Exceptions\QueryException($exception->getMessage(), $exception->getCode()))
                ->setQuery($sql);
        }

        return $result;
    }


    /**
     * {@inheritDoc}
     */
    public function makeArray($result, $index = false)
    {
        $rsArray = [];
        $iterator = 0;
        while ($row = $this->getRow($result)) {
            $returnIndex = $index !== false && isset($row[$index]) ? $row[$index] : $iterator;
            $rsArray[$returnIndex] = $row;
            $iterator++;
        }

        return $rsArray;
    }

    /**
     * @param PDOStatement|bool $result
     * @return int
     */
    protected function saveAffectedRows($result)
    {
        $this->affectedRows = \is_bool($result) ? 0 : $result->rowCount();
        return $this->getAffectedRows();
    }

    /**
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    /**
     * @param PDOStatement|bool $result
     * @return bool
     */
    public function execute($result)
    {
        return $this->isResult($result) ? $result->execute() : (bool)$result;
    }

    /**
     * @param string $sql
     * @return PDOStatement|bool
     * @throws Exceptions\ConnectException
     */
    public function prepare($sql)
    {
        $pdo = $this->getConnect()->getPdo();
        $result = $pdo->prepare(
            $sql,
            [
                \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL,

            ]
        );

        if ($this->isResult($result)) {
            $result->setFetchMode(\PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     * @return Connection
     */
    public function getConnect()
    {
        if (!$this->isConnected()) {
            $this->connect();
            if (!$this->conn->getPdo() instanceof PDO) {
                $this->conn->reconnect();
            }
        }
        return $this->conn;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return ($this->conn instanceof Connection && $this->conn->getDatabaseName());
    }

    public function insertFrom(
        $fields,
        $table,
        $fromFields = '*',
        $fromTable = '',
        $where = '',
        $limit = ''
    )
    {
        if (is_array($fields)) {
            $onlyKeys = true;
            foreach ($fields as $key => $value) {
                if (!empty($value)) {
                    $onlyKeys = false;
                    break;
                }
            }
            if ($onlyKeys) {
                $fields = array_keys($fields);
            }
        }

        return parent::insertFrom($fields, $table, $fromFields, $fromTable, $where, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function setDebug($flag)
    {
        parent::setDebug($flag);
        $driver = $this->getDriver();
        if ($driver instanceof Drivers\IlluminateDriver) {
            if ($this->isDebug()) {
                $driver->getConnect()->enableQueryLog();
            } else {
                $driver->getConnect()->disableQueryLog();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver()
    {
        return $this->driver;
    }

    public function getFullTableName($table)
    {
        return $this->getConnection()->getConfig('prefix') . $table;
    }
    public function getTableName($table)
    {
        return $this->getFullTableName($table);
    }

    public function getValue($result)
    {
        $out = false;
        if ($this->isResult($result)) {
            $result = $this->getRow($result, 'num');
            $out = is_array($result) && array_key_exists(0, $result) ? $result[0] : false;
        }

        return $out;
    }

    /**
     * {@inheritDoc}
     */
    public function isResult($result)
    {
        return $result instanceof PDOStatement;
    }

    /**
     * @param PDOStatement $result
     * {@inheritDoc}
     */
    public function getRow($result, $mode = 'assoc')
    {
        $result->execute();
        switch ($mode) {
            case 'assoc':
                $out = $result->fetch(\PDO::FETCH_ASSOC);
                break;
            case 'num':
                $out = $result->fetch(\PDO::FETCH_NUM);
                break;
            case 'object':
                $out = $result->fetchObject();
                break;
            case 'both':
                $out = $result->fetch(\PDO::FETCH_BOTH);
                break;
            default:
                throw new Exceptions\UnknownFetchTypeException(
                    "Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num', 'object' or 'both'."
                );
        }
        return $out;
    }


    /**
     * @param mysqli_result $result
     * {@inheritDoc}
     */
    public function getRow_2($result, $mode = 'assoc')
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
    public function select($fields, $tables, $where = '', $orderBy = '', $limit = '')
    {
        $fields = $this->prepareFields($fields);
        $tables = $this->prepareFrom($tables, true);
        $where = $this->prepareWhere($where);
        $orderBy = $this->prepareOrder($orderBy);
        $limit = $this->prepareLimit($limit);

        return $this->query("SELECT {$fields} FROM {$tables} {$where} {$orderBy} {$limit}");
    }

    /**
     * @param string|array $data
     * @param bool $ignoreAlias
     * @return string
     */
    protected function prepareFields($data, $ignoreAlias = false)
    {
        if (\is_array($data)) {
            $tmp = [];
            foreach ($data as $alias => $field) {
                $tmp[] = ($alias !== $field && !\is_int($alias) && $ignoreAlias === false) ?
                    ($field . ' as `' . $alias . '`') : $field;
            }

            $data = implode(',', $tmp);
        }
        if (empty($data)) {
            $data = '*';
        }

        return $data;
    }

    /**
     * @param string|array $data
     * @param bool $hasArray
     * @return string
     * @throws Exceptions\TableNotDefinedException
     */
    protected function prepareFrom($data, $hasArray = false)
    {
        if (\is_array($data) && $hasArray === true) {
            $tmp = [];
            foreach ($data as $table) {
                $tmp[] = $table;
            }
            $data = implode(' ', $tmp);
        }
        if (!is_scalar($data) || empty($data)) {
            throw new Exceptions\TableNotDefinedException($data);
        }

        return $data;
    }

    /**
     * @param array|string $data
     * @return string
     * @throws Exceptions\InvalidFieldException
     */
    protected function prepareWhere($data)
    {
        if (\is_array($data)) {
            if ($this->arrayOnlyNumeric(array_keys($data)) === true) {
                $data = implode(' ', $data);
            } else {
                throw (new Exceptions\InvalidFieldException('WHERE'))
                    ->setData($data);
            }
        }
        $data = trim($data);
        if (!empty($data) && stripos($data, 'WHERE') !== 0) {
            $data = "WHERE {$data}";
        }

        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    protected function prepareOrder($data)
    {
        $data = trim($data);
        if (!empty($data) && stripos($data, 'ORDER') !== 0) {
            $data = "ORDER BY {$data}";
        }

        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    protected function prepareLimit($data)
    {
        $data = trim($data);
        if (!empty($data) && stripos($data, 'LIMIT') !== 0) {
            $data = "LIMIT {$data}";
        }

        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function arrayOnlyNumeric(array $data)
    {
        $onlyNumbers = true;
        foreach ($data as $value) {
            if (!\is_numeric($value)) {
                $onlyNumbers = false;
                break;
            }
        }

        return $onlyNumbers;
    }

    public function getVersion()
    {
        return \DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }

    public function getConfig($option = null)
    {
        return $this->getConnection()->getConfig($option);
    }

    /**
     * {@inheritDoc}
     * @throws Exceptions\TooManyLoopsException
     */
    public function escape($data, $safeCount = 0)
    {
        $safeCount++;
        if ($this->safeLoopCount < $safeCount) {
            throw new Exceptions\TooManyLoopsException("Too many loops '{$safeCount}'");
        }
        if (\is_array($data)) {
            if (\count($data) === 0) {
                $data = '';
            } else {
                foreach ($data as $i => $v) {
                    $data[$i] = $this->escape($v, $safeCount);
                }
            }
        } else {
            if ($this->getConnection()->getDriverName() == 'mysqli') {
                $data = $this->getConnection()->getPdo()->quote($data);
            }
        }

        return $data;
    }
}
