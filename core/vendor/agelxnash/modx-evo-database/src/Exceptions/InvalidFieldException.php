<?php namespace AgelxNash\Modx\Evo\Database\Exceptions;

class InvalidFieldException extends Exception
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
