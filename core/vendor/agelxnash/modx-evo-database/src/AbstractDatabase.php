<?php namespace AgelxNash\Modx\Evo\Database;

abstract class AbstractDatabase implements Interfaces\DatabaseInterface, Interfaces\DebugInterface
{
    use Traits\DebugTrait,
        Traits\SupportTrait,
        Traits\ConfigTrait;

    /**
     * @var Interfaces\DriverInterface
     */
    protected $driver;

    /**
     * @var int
     */
    protected $safeLoopCount = 1000;

    /**
     * {@inheritDoc}
     */
    public function getLastError()
    {
        return $this->getDriver()->getLastError();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastErrorNo()
    {
        return (string)$this->getDriver()->getLastErrorNo();
    }

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        $tStart = microtime(true);

        $out = $this->getDriver()->getConnect();

        $totalTime = microtime(true) - $tStart;
        if ($this->isDebug()) {
            $this->setConnectionTime($totalTime);
        }
        $this->setCharset(
            $this->getConfig('charset'),
            $this->getConfig('method')
        );

        return $out;
    }

    /**
     * {@inheritDoc}
     */
    public function disconnect()
    {
        $this->getDriver()->disconnect();

        $this->setConnectionTime(0);
        $this->flushExecutedQuery();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isResult($result)
    {
        return $this->getDriver()->isResult($result);
    }

    /**
     * {@inheritDoc}
     */
    public function numFields($result)
    {
        return $this->getDriver()->numFields($result);
    }

    /**
     * {@inheritDoc}
     */
    public function fieldName($result, $col = 0)
    {
        return $this->getDriver()->fieldName($result, $col);
    }

    /**
     * {@inheritDoc}
     */
    public function setCharset($charset, $method = null)
    {
        return $this->getDriver()->setCharset($charset, $method);
    }

    /**
     * {@inheritDoc}
     */
    public function selectDb($name)
    {
        $tStart = microtime(true);

        $result = $this->getDriver()->selectDb($name);

        $this->addQueriesTime(microtime(true) - $tStart);

        return $result;
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
            $data = $this->getDriver()->escape($data);
        }

        return $data;
    }

    /**
     * @param string|array $sql
     * {@inheritDoc}
     */
    public function query($sql)
    {
        $tStart = microtime(true);
        if (\is_array($sql)) {
            $sql = implode("\n", $sql);
        }
        $this->setLastQuery($sql);

        $result = $this->getDriver()->query(
            $this->getLastQuery()
        );

        if ($result === false) {
            /**
             * @TODO: NOT WORK?
             */
            $this->checkLastError($this->getLastQuery());
        } else {
            $tend = microtime(true);
            $totalTime = $tend - $tStart;
            $this->addQueriesTime($totalTime);
            if ($this->isDebug()) {
                $this->collectQuery(
                    $result,
                    $this->getLastQuery(),
                    $totalTime
                );
            }
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getRecordCount($result)
    {
        return $this->getDriver()->getRecordCount($result);
    }

    /**
     * {@inheritDoc}
     */
    public function getRow($result, $mode = 'assoc')
    {
        if (\is_scalar($result)) {
            $result = $this->query($result);
        }

        return $this->getDriver()->getRow($result, $mode);
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {
        return $this->getDriver()->getVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function getInsertId()
    {
        return $this->convertValue(
            $this->getDriver()->getInsertId()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getAffectedRows()
    {
        return $this->getDriver()->getAffectedRows();
    }

    /**
     * {@inheritDoc}
     */
    public function getColumn($name, $result)
    {
        if (\is_scalar($result)) {
            $result = $this->query($result);
        }

        return $this->getDriver()->getColumn($name, $result);
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnNames($result)
    {
        if (\is_scalar($result)) {
            $result = $this->query($result);
        }
        return $this->getDriver()->getColumnNames($result);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($result)
    {
        if (\is_scalar($result)) {
            $result = $this->query($result);
        }

        return $this->convertValue(
            $this->getDriver()->getValue($result)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getTableMetaData($table)
    {
        $metadata = [];
        if (! empty($table)) {
            $sql = 'SHOW FIELDS FROM ' . $table;
            $result = $this->query($sql);
            $metadata = $this->getDriver()->getTableMetaData($result);
        }

        return $metadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * {@inheritDoc}
     */
    public function setDriver($driver)
    {
        if (! \in_array(Interfaces\DriverInterface::class, class_implements($driver), true)) {
            throw new Exceptions\DriverException(
                $driver . ' should implements the ' . Interfaces\DriverInterface::class
            );
        }

        if (is_scalar($driver)) {
            $this->driver = new $driver($this->getConfig());
        } else {
            $this->driver = $driver;
            $this->config = array_merge($this->config, $driver->getConfig());
        }

        return $this->driver;
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
     * {@inheritDoc}
     */
    public function delete($table, $where = '', $orderBy = '', $limit = '')
    {
        $table = $this->prepareFrom($table);
        $where = $this->prepareWhere($where);
        $orderBy = $this->prepareOrder($orderBy);
        $limit = $this->prepareLimit($limit);

        $result = $this->query("DELETE FROM {$table} {$where} {$orderBy} {$limit}");
        return $this->isResult($result) ? true : $result;
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
     * {@inheritDoc}
     */
    public function update($values, $table, $where = '')
    {
        $table = $this->prepareFrom($table);
        $values = $this->prepareValuesSet($values);
        if (mb_strtoupper(mb_substr($values, 0, 4)) !== 'SET ') {
            $values = 'SET ' . $values;
        }
        $where = $this->prepareWhere($where);

        $result = $this->query("UPDATE {$table} {$values} {$where}");
        return $this->isResult($result) ? true : $result;
    }

    /**
     * {@inheritDoc}
     */
    public function insert(
        $fields,
        $table,
        $fromFields = '*',
        $fromTable = '',
        $where = '',
        $limit = ''
    ) {
        $lid = null;

        if (empty($fromTable)) {
            $table = $this->prepareFrom($table);
            $useFields = \is_array($fields) ? $this->prepareValues($fields) : $fields;

            if (empty($useFields) || ! \is_scalar($useFields) || $useFields === '*') {
                throw (new Exceptions\InvalidFieldException('Invalid insert fields'))
                    ->setData($fields);
            }

            $this->query("INSERT INTO {$table} {$useFields}");
        } else {
            $lid = $this->insertFrom($fields, $table, $fromFields, $fromTable, $where, $limit);
        }

        if ($lid === null && ($lid = $this->getInsertId()) === false) {
            throw new Exceptions\GetDataException("Couldn't get last insert key!");
        }

        return $this->convertValue($lid);
    }

    /**
     * @param string|array $fields
     * @param string $table
     * @param string|array $fromFields
     * @param string $fromTable
     * @param string|array $where
     * @param string $limit
     * @return mixed
     * @throws Exceptions\InvalidFieldException
     * @throws Exceptions\TableNotDefinedException
     */
    public function insertFrom(
        $fields,
        $table,
        $fromFields = '*',
        $fromTable = '',
        $where = '',
        $limit = ''
    ) {
        $table = $this->prepareFrom($table);
        $useFields = \is_array($fields) ? $this->prepareFields($fields, true) : $fields;

        if (empty($useFields) || ! \is_scalar($useFields) || $useFields === '*') {
            throw (new Exceptions\InvalidFieldException('Invalid insert fields'))
                ->setData($fields);
        }

        if (empty($fromFields) || $fromFields === '*') {
            $fromFields = $this->prepareFields($fields, true);
        } else {
            $fromFields = $this->prepareFields($fromFields, true);
        }

        $where = $this->prepareWhere($where);
        $limit = $this->prepareLimit($limit);

        $lid = $this->query(
            "INSERT INTO {$table} ({$useFields}) SELECT {$fromFields} FROM {$fromTable} {$where} {$limit}"
        );

        $lid = $this->isResult($lid) ? true : $lid;
        if ($lid === true && $this->getInsertId() > 0) {
            $lid = $this->getInsertId();
        }
        return $lid;
    }

    /**
     * {@inheritDoc}
     */
    public function save($fields, $table, $where = '')
    {
        if ($where === '') {
            $mode = 'insert';
        } else {
            $result = $this->select('*', $table, $where);

            if ($this->getRecordCount($result) === 0) {
                $mode = 'insert';
            } else {
                $mode = 'update';
            }
        }

        return ($mode === 'insert') ? $this->insert($fields, $table) : $this->update($fields, $table, $where);
    }

    /**
     * {@inheritDoc}
     */
    public function optimize($table)
    {
        $result = $this->query('OPTIMIZE TABLE ' . $table);
        if ($result !== false) {
            $result = $this->alterTable($table);
        }

        return $this->isResult($result) ? true : $result;
    }

    /**
     * {@inheritDoc}
     */
    public function alterTable($table)
    {
        $result = $this->query('ALTER TABLE ' . $table);

        return $this->isResult($result) ? true : $result;
    }

    /**
     * {@inheritDoc}
     */
    public function truncate($table)
    {
        $result = $this->query('TRUNCATE ' . $table);

        return $this->isResult($result) ? $this->getValue($result) : $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getTableName($table, $escape = true)
    {
        if (empty($table)) {
            throw new Exceptions\TableNotDefinedException($table);
        }

        $out = $this->getConfig('prefix') . $table;

        return $escape ? '`' . $out . '`' : $out;
    }

    /**
     * {@inheritDoc}
     */
    public function getFullTableName($table)
    {
        if (empty($table)) {
            throw new Exceptions\TableNotDefinedException($table);
        }

        return implode('.', [
            '`' . $this->getConfig('database') . '`',
            $this->getTableName($table)
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function begin ($flag = 0, $name = null)
    {
        return $this->getDriver()->begin($flag, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function commit ($flag = 0, $name = null)
    {
        return $this->getDriver()->commit($flag, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function rollback ($flag = 0, $name = null)
    {
        return $this->getDriver()->rollback($flag, $name);
    }


}
