<?php namespace Helpers;

use Closure;

/**
 * @see: https://github.com/laravel/framework/blob/4.2/src/Illuminate/Support/Collection.php
 */
class Collection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * Collection constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return static
     */
    public function create(array $data = array())
    {
        return new static($data);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @param Closure $func
     * @return Collection
     */
    public function map(Closure $func)
    {
        return $this->create(array_map($func, $this->data));
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  Closure $callback
     * @return static
     */
    public function filter(Closure $callback)
    {
        return $this->create(array_filter($this->data, $callback));
    }

    /**
     * @param Closure $p
     * @return bool
     */
    public function forAll(Closure $p)
    {
        foreach ($this->data as $key => $element) {
            if (! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Closure $p
     * @return array
     */
    public function partition(Closure $p)
    {
        $matches = $noMatches = array();
        foreach ($this->data as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return array($this->create($matches), $this->create($noMatches));
    }

    /**
     * @param $offset
     * @param null|int $length
     * @return array
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->data, $offset, $length, true);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->data = array();

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function append($value)
    {
        $this->data[] = $value;

        return $this;
    }

    /**
     * @param $data
     * @param null|int|string $id
     * @return $this
     */
    public function add($data, $id = null)
    {
        if ((is_int($id) || is_string($id)) && $id !== '') {
            $this->data[$id] = $data;
        } else {
            $this->append($data);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function get($id)
    {
        $out = null;
        if (is_scalar($id) && $id !== '' && $this->containsKey($id)) {
            $out = $this->data[$id];
        }

        return $out;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->data);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this->data);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @return mixed
     */
    public function prev()
    {
        return prev($this->data);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function remove($key)
    {
        if (! isset($this->data[$key]) && ! array_key_exists($key, $this->data)) {
            return null;
        }
        $removed = $this->data[$key];
        unset($this->data[$key]);

        return $removed;
    }

    /**
     * @param $element
     * @return bool
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->data, true);
        if ($key === false) {
            return false;
        }
        unset($this->data[$key]);

        return true;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return Collection
     */
    public function offsetSet($offset, $value)
    {
        if ($offset !== null) {
            $this->set($offset, $value);
        } else {
            $this->add($value);
        }

        return $this;
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * @param $key
     * @return bool
     */
    public function containsKey($key)
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }

    /**
     * @param $element
     * @return bool
     */
    public function contains($element)
    {
        return in_array($element, $this->data, true);
    }

    /**
     * @param Closure $p
     * @return bool
     */
    public function exists(Closure $p)
    {
        foreach ($this->data as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $element
     * @return mixed
     */
    public function indexOf($element)
    {
        return array_search($element, $this->data, true);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->data);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return array_values($this->data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param  callable $callback
     * @param  mixed $initial
     * @return mixed
     */
    public function reduce(Closure $callback, $initial = null)
    {
        return array_reduce($this->data, $callback, $initial);
    }

    /**
     * Get the max value of a given key.
     *
     * @param  string $key
     * @return mixed
     */
    public function max($key)
    {
        return $this->reduce(function ($result, $item) use ($key) {
            return (is_null($result) || $item[$key] > $result) ? $item[$key] : $result;
        });
    }

    /**
     * Get the min value of a given key.
     *
     * @param  string $key
     * @return mixed
     */
    public function min($key)
    {
        return $this->reduce(function ($result, $item) use ($key) {
            return (is_null($result) || $item[$key] < $result) ? $item[$key] : $result;
        });
    }

    /**
     * Sort through each item with a callback.
     *
     * @param  Closure $callback
     * @return $this
     */
    public function sort(Closure $callback)
    {
        uasort($this->data, $callback);

        return $this;
    }

    /**
     * @param  Closure $callback
     * @return $this
     */
    public function ksort(Closure $callback)
    {
        uksort($this->data, $callback);

        return $this;
    }

    /**
     * @return $this
     */
    public function reindex()
    {
        $this->data = array_values($this->data);

        return $this;
    }

    /**
     * Return only unique items from the collection array.
     *
     * @return static
     */
    public function unique()
    {
        return $this->create(array_unique($this->data));
    }

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse()
    {
        return $this->create(array_reverse($this->data));
    }

}
