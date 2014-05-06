<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form;

/**
 * Field error bag
 *
 * @package  Moss Form
 * @author   Michal Wachowski <wachowski.michal@gmail.com>
 */
class ErrorBag implements BagInterface
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

        return isset($this->storage[$offset]) ? $this->storage[$offset] : $default;
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
            array_push($this->storage, $value);
            return $this;
        }

        $this->storage[$offset] = $value;

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
        if ($offset !== null) {
            return isset($this->storage[$offset]);
        }

        return $this->count() > 0;
    }

    /**
     * Adds value or values to offset
     * Creates offset if it does not exists
     *
     * @param string       $offset offset to add to
     * @param string|array $value  value or array of values added
     *
     * @return $this
     */
    public function add($offset, $value)
    {
        if ($offset === null) {
            array_push($this->storage, $value);

            return $this;
        }

        $this->storage[$offset][] = $value;

        return $this;
    }


    /**
     * Removes offset from bag
     * If no offset set, removes all values
     *
     * @param string      $offset offset to remove from
     * @param null|string $value  value to remove
     *
     * @return $this
     */
    public function remove($offset = null, $value = null)
    {
        if ($offset == null) {
            $this->storage = array();
        }

        if (!isset($this->storage[$offset])) {
            return $this;
        }

        if ($value !== null) {
            while (!null === $i = array_search($value, $this->storage[$offset])) {
                unset($this->storage[$offset][$i]);
            }

            return $this;
        }

        unset($this->storage[$offset]);

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
            $this->storage = $array;
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
    public function offsetGet($offset)
    {
        return $this->get($offset);
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
        $this->set($offset);
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
        $this->remove($offset);
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

    /**
     * Returns error messages as string
     *
     * @param array $elements
     *
     * @return string
     */
    public function render(array $elements = array())
    {
        $storage = array_merge($elements, $this->storage);
        if (empty($storage)) {
            return '';
        }

        $result = array();
        foreach ($storage as $msg) {
            $result[] = sprintf('<li>%s</li>', $msg);
        }

        return sprintf('<ul class="error">%s</ul>', implode('', $result));
    }

    /**
     * Returns error messages as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}