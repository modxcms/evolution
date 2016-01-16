<?php
class xNop
{
    /**
     * Magic call
     *
     * @param $method
     * @param $args
     * @return null
     */
    public function __call($method, $args)
    {
        return null;
    }

    /**
     * Magic call for static
     *
     * @param $method
     * @param $args
     * @return null
     */
    public static function __callStatic($method, $args)
    {
        return null;
    }

    /**
     * __set
     *
     * @param $key
     * @param $value
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