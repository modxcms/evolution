<?php namespace AgelxNash\Modx\Evo\Database\Drivers;

use AgelxNash\Modx\Evo\Database\Exceptions;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use PDOStatement;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Container\Container;
use ReflectionClass;
use PDO;

/**
 * @property Connection $conn
 */
class IlluminateDriver extends AbstractDriver
{
    /**
     * @var string
     */
    protected $connection = 'default';

    /**
     * @var Capsule
     */
    protected $capsule;

    /**
     * @var int
     */
    protected $affectedRows = 0;

    /**
     * @var array
     */
    protected $lastError = [];

    /**
     * @var string
     */
    protected $lastErrorNo = '';

    /**
     * @var string
     */
    protected $driver = 'mysql';

    private $elapsedTimeMethod;

    /**
     * {@inheritDoc}
     * @throws \ReflectionException
     */
    public function __construct(array $config = [], $connection = 'default')
    {
        $reflection = new ReflectionClass(Capsule::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        /**
         * @var Capsule|null $capsule
         */
        $capsule = $property->getValue(Capsule::class);
        if ($capsule === null) {
            $this->capsule = new Capsule;

            $this->getCapsule()->setAsGlobal();
        } else {
            $this->capsule = $capsule;
        }

        if ($this->hasConnectionName($connection)) {
            if (empty($config)) {
                $config = $this->getCapsule()->getConnection($connection)->getConfig();
                unset($config['name'], $config['driver']);
            } else {
                $diff = array_diff_assoc(
                    array_merge(['driver' => $this->driver], $config),
                    $this->getCapsule()->getConnection($connection)->getConfig()
                );
                if (array_intersect(['driver', 'host', 'database', 'password', 'username'], array_keys($diff))) {
                    throw new Exceptions\ConnectException(
                        sprintf('The connection name "%s" is already used', $connection)
                    );
                }
            }
        }

        $this->connection = $connection;

        $this->useEloquent();

        $this->setConfig($config);
    }

    /**
     * Get the elapsed time since a given starting point.
     *
     * @param  int    $start
     * @return float
     */
    protected function getElapsedTime($start)
    {
        if ($this->elapsedTimeMethod === null) {
            $reflection = new ReflectionClass($this->getConnect());
            $this->elapsedTimeMethod = $reflection->getMethod('getElapsedTime');
            $this->elapsedTimeMethod->setAccessible(true);
        }

        return $this->elapsedTimeMethod->invoke($this->getConnect(), $start);
    }
    /**
     * {@inheritDoc}
     * @return Connection
     */
    public function getConnect()
    {
        if (! $this->isConnected()) {
            $this->connect();
            if (! $this->conn->getPdo() instanceof PDO) {
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

    /**
     * {@inheritDoc}
     */
    public function getLastError()
    {
        $error = $this->getConnect()->getPdo()->errorInfo();
        return empty($error[2]) ? (empty($this->lastError[2]) ? '' : $this->lastError[2]) : $error[2];
    }

    /**
     * {@inheritDoc}
     */
    public function getLastErrorNo()
    {
        $error = $this->getConnect()->getPdo()->errorInfo();
        $out = empty($error[0]) || $error[0] === '00000' ? $this->lastErrorNo : $error[0];

        return $out;
    }

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        try {
            if (! $this->hasConnectionName($this->connection)) {
                $this->getCapsule()->addConnection([
                    'driver'    => $this->driver,
                    'host'      => $this->getConfig('host'),
                    'database'  => $this->getConfig('database'),
                    'username'  => $this->getConfig('username'),
                    'password'  => $this->getConfig('password'),
                    'charset'   => $this->getConfig('charset'),
                    'collation' => $this->getConfig('collation'),
                    'prefix'    => $this->getConfig('prefix'),
                ], $this->connection);
            }

            $this->conn = $this->getCapsule()->getConnection($this->connection);
        } catch (\Exception $exception) {
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
            $this->conn->disconnect();
        }

        $this->conn = null;
        $this->lastErrorNo = '';
        $this->lastError = [];

        return true;
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
    public function numFields($result)
    {
        return $this->isResult($result) ? $result->columnCount() : 0;
    }

    /**
     * @param PDOStatement $result
     * {@inheritDoc}
     */
    public function fieldName($result, $col = 0)
    {
        $field = $this->isResult($result) ? $result->getColumnMeta($col) : [];
        return isset($field['name']) ? $field['name'] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setCharset($charset, $method = null)
    {
        if ($method === null) {
            $method = $this->getConfig('method');
        }

        $query = $method . ' ' . $charset;

        return (bool)$this->query($query);
    }

    /**
     * {@inheritDoc}
     */
    public function selectDb($name)
    {
        return $this->getConnect()->getPdo()->exec('USE ' . $name) === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function escape($data)
    {
        /**
         * It's not secure
         * But need for backward compatibility
         */

        $quote = $this->getConnect()->getPdo()->quote($data);
        return strpos($quote, '\'') === 0 ? mb_substr($quote, 1, -1) : $quote;
    }

    /**
     * {@inheritDoc}
     * @return bool|PDOStatement
     */
    public function query($sql)
    {
        try {
            $start = microtime(true);

            $result = $this->prepare($sql);
            $this->execute($result);

            if ($this->saveAffectedRows($result) === 0 && $this->isResult($result) && ! $this->isSelectQuery($sql)) {
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
     * @param PDOStatement $result
     * {@inheritDoc}
     */
    public function getRecordCount($result)
    {
        return $this->isResult($result) ? $result->rowCount() : 0;
    }

    /**
     * @param PDOStatement $result
     * {@inheritDoc}
     */
    public function getRow($result, $mode = 'assoc')
    {
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
     * {@inheritDoc}
     */
    public function getVersion()
    {
        return $this->getConnect()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }

    /**
     * {@inheritDoc}
     */
    public function getInsertId()
    {
        return $this->getConnect()->getPdo()->lastInsertId();
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
     * @return int
     */
    protected function saveAffectedRows($result)
    {
        $this->affectedRows = \is_bool($result) ? 0 : $result->rowCount();
        return $this->getAffectedRows();
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
     * @param PDOStatement|bool $result
     * @return bool
     */
    public function execute($result)
    {
        return $this->isResult($result) ? $result->execute() : (bool)$result;
    }

    /**
     * @param DispatcherContract|null $dispatcher
     * @return bool
     */
    public function useEloquent(DispatcherContract $dispatcher = null)
    {
        $out = false;
        if ($dispatcher === null) {
            $dispatcher = $this->getCapsule()->getEventDispatcher();
        }

        if ($dispatcher === null && class_exists(Dispatcher::class)) {
            $dispatcher = new Dispatcher(new Container);
        }

        if ($dispatcher !== null) {
            $this->getCapsule()->setEventDispatcher($dispatcher);

            $out = true;
        }

        $this->getCapsule()->bootEloquent();

        return $out;
    }

    /**
     * @return Capsule
     */
    public function getCapsule()
    {
        return $this->capsule;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasConnectionName($name)
    {
        $connections = $this->getCapsule()->getDatabaseManager()->getConnections();
        return isset($connections[$name]);
    }

    /**
     * @param string $query
     * @return bool
     */
    protected function isSelectQuery($query)
    {
        return 0 === mb_stripos(trim($query), 'SELECT');
    }

    /**
     * {@inheritDoc}
     */
    public function begin ($flag = 0, $name = null)
    {
        return $this->getConnect()->getPdo()->beginTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function commit ($flag = 0, $name = null)
    {
        return $this->getConnect()->getPdo()->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function rollback ($flag = 0, $name = null)
    {
        return $this->getConnect()->getPdo()->rollback();
    }
}
