<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form\Bag;

/**
 * Abstract bag
 *
 * @package  Form Bag
 * @author   Michal Wachowski <wachowski.michal@gmail.com>
 */
class AbstractBag implements BagInterface
{
    protected $storage = array();

    /**
     * Construct
     *
     * @param array $storage
     */
    public function __construct($storage = array())
    {
        $this->all($storage);
    }

    /**
     * Retrieves offset value
     *
     * @param string $offset
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($offset = null, $default = null)
    {
        if ($offset === null) {
            return $this->all();
        }

        return $this->has($offset) ? $this[$offset] : $default;
    }

    /**
     * Sets value to offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($offset, $value = null)
    {
        if ($offset === null) {
            $this[] = $value;

            return $this;
        }

        if (is_array($offset)) {
            foreach ($offset as $key => $value) {
                $this[$key] = $value;
            }

            return $this;
        }

        $this[$offset] = $value;

        return $this;
    }

    /**
     * Returns true if offset exists in bag
     *
     * @param string $offset
     *
     * @return bool
     */
    public function has($offset = null)
    {
        if ($offset === null) {
            return $this->count() > 0;
        }

        return array_key_exists($offset, $this->storage);
    }

    /**
     * Removes offset from bag
     * If no offset set, removes all values
     *
     * @param string $offset attribute to remove from
     *
     * @return $this
     */
    public function remove($offset = null)
    {
        if ($offset === null) {
            $this->reset();

            return $this;
        }

        $this->offsetUnset($offset);

        return $this;
    }

    /**
     * Returns all options
     * If array passed, becomes bag content
     *
     * @param array $array overwrites values
     *
     * @return array
     */
    public function all($array = array())
    {
        if ($array !== array()) {
            $this->reset();

            foreach ((array) $array as $offset => $value) {
                $this[$offset] = $value;
            }
        }

        return $this->storage;
    }

    /**
     * Removes all options
     *
     * @return $this
     */
    public function reset()
    {
        $this->storage = array();

        return $this;
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     *
     * @return mixed Can return all value types.
     */
    public function &offsetGet($offset)
    {
        if (!$this->has($offset)) {
            $this->storage[$offset] = null;
        }

        return $this->storage[$offset];
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            array_push($this->storage, $value);

            return;
        }

        $this->storage[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
    }

    /**
     * Count elements of an object
     *
     * @return int
     */
    public function count()
    {
        return count($this->storage);
    }

    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->storage);
    }

    /**
     * Return the key of the current element
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->storage);
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        next($this->storage);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->storage);
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        $offset = key($this->storage);

        if ($offset === false || $offset === null) {
            return false;
        }

        return isset($this->storage[$offset]);
    }
}
