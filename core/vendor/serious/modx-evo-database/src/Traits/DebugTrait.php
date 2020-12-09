<?php namespace AgelxNash\Modx\Evo\Database\Traits;

use AgelxNash\Modx\Evo\Database\Exceptions;

trait DebugTrait
{
    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var array
     */
    protected $queryCollection = [];

    /**
     * @var int
     */
    protected $queriesTime = 0;

    /**
     * @var string
     */
    protected $lastQuery = '';

    /**
     * @var int
     */
    protected $connectionTime = 0;

    /**
     * @var array
     */
    protected $ignoreErrors = [
        '42S22', // SQLSTATE: 42S22 (ER_BAD_FIELD_ERROR) Unknown column '%s' in '%s'
        '42S21', // SQLSTATE: 42S21 (ER_DUP_FIELDNAME) Duplicate column name '%s'
        '42000', // SQLSTATE: 42000 (ER_DUP_KEYNAME) Duplicate key name '%s'
        '23000', // SQLSTATE: 23000 (ER_DUP_ENTRY) Duplicate entry '%s' for key %d
        '42000' // SQLSTATE: 42000 (ER_CANT_DROP_FIELD_OR_KEY) Can't DROP '%s'; check that column/key exists
    ];

    /**
     * @var string
     */
    protected $timeFormat = '%2.5f';

    /**
     * {@inheritDoc}
     */
    abstract public function getDriver();

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
    abstract public function getRecordCount($result);

    /**
     * {@inheritDoc}
     */
    abstract public function getAffectedRows();

    /**
     * {@inheritDoc}
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * {@inheritDoc}
     */
    public function setDebug($flag)
    {
        $this->debug = $flag;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function checkLastError($query = null)
    {
        if ($this->getDriver()->isConnected() === false || $this->getLastErrorNo() === '') {
            return false;
        }

        if (\in_array($this->getLastErrorNo(), $this->getIgnoreErrors(), true)) {
            return true;
        }

        throw (new Exceptions\QueryException($this->getLastError()))
            ->setQuery($query);
    }

    /**
     * {@inheritDoc}
     */
    public function collectQuery($result, $sql, $time)
    {
        $debug = debug_backtrace();
        array_shift($debug);
        array_shift($debug);
        $path = [];
        foreach ($debug as $line) {
            $path[] = ($line['class'] ? ($line['class'] . '::') : null) . $line['function'];
            /*$path[] = [
                'method' => ($line['class'] ? ($line['class'] . '::') : null) . $line['function'],
                'file' => ($line['file'] ? ($line['file'] . ':') : null) . ($line['line'] ?? 0)
            ];*/
        }
        $path = implode(' > ', array_reverse($path));

        $data = [
            'sql' => $sql,
            'time' => $time,
            'rows' => (stripos($sql, 'SELECT') === 0 && $this->getDriver()->isResult($result)) ?
                $this->getRecordCount($result) : $this->getAffectedRows(),
            'path' => $path,
            //'event' => $modx->event->name,
            //'element' => [
            //      'name' => $modx->event->activePlugin ?? ($modx->currentSnippet ?? null),
            //      'type' => $modx->event->activePlugin ? 'Plugin' : ($modx->currentSnippet ? 'Snippet' : 'Source code')
            // ]
        ];

        $this->queryCollection[] = $data;

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function setLastQuery($query)
    {
        $this->lastQuery = (string)$query;

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastQuery()
    {
        return (string)$this->lastQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllExecutedQuery()
    {
        return $this->queryCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function flushExecutedQuery()
    {
        $this->queryCollection = [];
        $this->queriesTime = 0;
        $this->lastQuery = $this->setLastQuery('');

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setConnectionTime($value)
    {
        $this->connectionTime = $value;

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionTime($format = false)
    {
        return $format ? sprintf($this->timeFormat, $this->connectionTime) : $this->connectionTime;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueriesTime()
    {
        return $this->queriesTime;
    }

    /**
     * {@inheritDoc}
     */
    public function addQueriesTime($time)
    {
        $this->queriesTime += $time;

        return $time;
    }

    /**
     * @return string
     */
    public function renderConnectionTime()
    {
        return '<fieldset style="text-align:left">' .
            '<legend>Database connection</legend>' .
            'Database connection was created in ' . $this->getConnectionTime(true) . ' s.' .
            '</fieldset>' .
            '<br />';
    }

    /**
     * @return array
     */
    public function getIgnoreErrors()
    {
        return $this->ignoreErrors;
    }

    /**
     * @param string $error
     * @return string
     */
    public function addIgnoreErrors($error)
    {
        $this->ignoreErrors[] = $error;

        return $error;
    }

    /**
     * @return bool
     */
    public function flushIgnoreErrors()
    {
        $this->ignoreErrors = [];

        return true;
    }

    /**
     * @param array $errors
     * @return array
     */
    public function setIgnoreErrors(array $errors)
    {
        $this->flushIgnoreErrors();

        foreach ($errors as $error) {
            $this->addIgnoreErrors($error);
        }

        return $this->getIgnoreErrors();
    }

    /**
     * @return string
     */
    public function renderExecutedQuery()
    {
        $out = '';

        foreach ($this->getAllExecutedQuery() as $i => $query) {
            $out .= '<fieldset style="text-align:left">';
            $out .= '<legend>Query ' . $i . ' - ' . sprintf($this->timeFormat, $query['time']) . '</legend>';
            $out .= $query['sql'] . '<br><br>';
            if (! empty($query['element'])) {
                $out .= $query['element']['type'] . '  => ' . $query['element']['name'] . '<br>';
            }
            if (! empty($query['event'])) {
                $out .= 'Current Event  => ' . $query['event'] . '<br>';
            }
            $out .= 'Affected Rows => ' . $query['rows'] . '<br>';
            if (! empty($query['path'])) {
                $out .= 'Functions Path => ' . $query['path'] . '<br>';
            }
            /*$out .= 'Functions Path => ' . $query['path']['method'] . '<br>';
            $out .= empty($query['path']['file']) ?: $query['path']['file'] . '<br />';*/
            $out .= '</fieldset><br />';
        }

        return $out;
    }
}
