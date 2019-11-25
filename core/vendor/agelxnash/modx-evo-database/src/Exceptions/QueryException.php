<?php namespace AgelxNash\Modx\Evo\Database\Exceptions;

use Throwable;

class QueryException extends Exception
{
    protected $query = '';

    /**
     * @param string $message
     * @param int|string $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, (int)$code, $previous);

        $this->code = $code;
    }

    /**
     * @param string $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }
}
