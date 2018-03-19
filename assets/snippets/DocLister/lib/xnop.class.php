<?php

/**
 * Class xNop
 */
class xNop
{

    /**
     * Magic call
     *
     * @param string $method
     * @param mixed $args
     * @return null
     */
    public function __call($method, $args)
    {
        return null;
    }

    /**
     * Magic call for static
     *
     * @param string $method
     * @param mixed $args
     * @return null
     */
    public static function __callStatic($method, $args)
    {
        return null;
    }

    /**
     * __set
     *
     * @param string $key
     * @param mixed $value
     * @return null
     */
    public function __set($key, $value)
    {
        return null;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }

}
