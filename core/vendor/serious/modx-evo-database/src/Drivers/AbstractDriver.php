<?php namespace AgelxNash\Modx\Evo\Database\Drivers;

use AgelxNash\Modx\Evo\Database\Interfaces\DriverInterface;
use AgelxNash\Modx\Evo\Database\Traits\ConfigTrait;

abstract class AbstractDriver implements DriverInterface
{
    use ConfigTrait;

    /**
     * @var mixed
     */
    protected $conn;

    /**
     * {@inheritDoc}
     */
    abstract public function __construct(array $config = []);

    /**
     * {@inheritDoc}
     */
    abstract public function getConnect();

    /**
     * {@inheritDoc}
     */
    abstract public function isConnected();

    /**
     * {@inheritDoc}
     */
    abstract public function getLastError();

    /**
     * {@inheritDoc}
     */
    abstract public function getLastErrorNo();

    /**
     * {@inheritDoc}
     */
    abstract public function connect();

    /**
     * {@inheritDoc}
     */
    abstract public function disconnect();

    /**
     * {@inheritDoc}
     */
    abstract public function isResult($result);

    /**
     * {@inheritDoc}
     */
    abstract public function numFields($result);

    /**
     * {@inheritDoc}
     */
    abstract public function fieldName($result, $col = 0);

    /**
     * {@inheritDoc}
     */
    abstract public function setCharset($charset, $method = null);

    /**
     * {@inheritDoc}
     */
    abstract public function selectDb($name);

    /**
     * {@inheritDoc}
     */
    abstract public function escape($data);

    /**
     * {@inheritDoc}
     */
    abstract public function query($sql);

    /**
     * {@inheritDoc}
     */
    abstract public function getRecordCount($result);

    /**
     * {@inheritDoc}
     */
    abstract public function getRow($result, $mode = 'assoc');

    /**
     * {@inheritDoc}
     */
    abstract public function getVersion();

    /**
     * {@inheritDoc}
     */
    abstract public function getInsertId();

    /**
     * {@inheritDoc}
     */
    abstract public function begin($flag = 0, $name = null);

    /**
     * {@inheritDoc}
     */
    abstract public function commit($flag = 0, $name = null);

    /**
     * {@inheritDoc}
     */
    abstract public function rollback($flag = 0, $name = null);

    /**
     * {@inheritDoc}
     */
    abstract public function getAffectedRows();

    /**
     * {@inheritDoc}
     */
    public function getColumn($name, $result)
    {
        $col = [];

        if ($this->isResult($result)) {
            while ($row = $this->getRow($result)) {
                if (array_key_exists($name, $row)) {
                    $col[] = $row[$name];
                }
            }
        }

        return $col;
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnNames($result)
    {
        $names = [];

        if ($this->isResult($result)) {
            $limit = $this->numFields($result);
            for ($i = 0; $i < $limit; $i++) {
                $names[] = $this->fieldName($result, $i);
            }
        }

        return $names;
    }

    /**
     * {@inheritDoc}
     */
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
    public function getTableMetaData($result)
    {
        $out = [];

        if ($this->isResult($result)) {
            while ($row = $this->getRow($result)) {
                $fieldName = $row['Field'];
                $out[$fieldName] = $row;
            }
        }

        return $out;
    }
}
