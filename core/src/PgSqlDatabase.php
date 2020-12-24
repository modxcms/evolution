<?php namespace EvolutionCMS;

use AgelxNash\Modx\Evo\Database\Drivers;
use Exception;
use AgelxNash\Modx\Evo\Database\Exceptions;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;

class PgSqlDatabase extends Manager
{
    public $config;

    public function __construct(Container $container=null)
    {
        var_dump($container);
        exit();
        parent::__construct($container);
        $this->config['table_prefix'] = $this->getConfig('prefix');
    }

    public function getFullTableName($table)
    {
        return $this->getTableName($table, false);
    }

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
            $result = $this->getFullTableName($tableName);
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
            $out = [];
            if (\is_array($sql)) {
                foreach ($sql as $query) {
                    $out[] = parent::query($this->replaceFullTableName($query));
                }
            } else {
                $out = parent::query($this->replaceFullTableName($sql));
            }

            return $out;
        } catch (Exception $exception) {
            if ($watchError === true) {
                evolutionCMS()->getService('ExceptionHandler')->messageQuit($exception->getMessage());
            }
        }
    }

    public function insertFrom(
        $fields,
        $table,
        $fromFields = '*',
        $fromTable = '',
        $where = '',
        $limit = ''
    ) {
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
}
