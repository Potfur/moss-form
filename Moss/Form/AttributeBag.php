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
 * Field attribute bag
 *
 * @package  Moss Form
 * @author   Michal Wachowski <wachowski.michal@gmail.com>
 */
class AttributeBag
{
    protected $storage = array();

    /**
     * List of multi valued attributes
     *
     * @var array
     */
    private $multiValue = array(
        'class'
    );

    /**
     * List of escaped attributes where key is attribute name and value is if strict or not
     *
     * @var array
     */
    private $escapedValues = array(
        'id' => true,
        'name' => false
    );

    /**
     * Construct
     *
     * @param array $storage
     * @param array $multiValue
     */
    public function __construct($storage = array(), $multiValue = array('class'))
    {
        $this->all($storage);
        $this->multiValue = $multiValue;
    }

    /**
     * Returns true if multi value offset
     *
     * @param string $offset
     *
     * @return bool
     */
    private function isMultiValue($offset)
    {
        return in_array($offset, $this->multiValue);
    }

    /**
     * Returns filtered array, without empty attributes
     *
     * @return array
     */
    private function filter()
    {
        return array_filter($this->storage);
    }

    /**
     * Asserts value type for offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @throws AttributeException
     */
    private function assertArrayValue($offset, $value)
    {
        if ($this->isMultiValue($offset) && !is_array($value)) {
            throw new AttributeException(sprintf('Invalid value for attribute %s, only array values, got %s', $offset, gettype($value)));
        }

        if (!$this->isMultiValue($offset) && (is_array($value) || is_object($value))) {
            throw new AttributeException(sprintf('Invalid value for attribute %s, only scalar values, got %s', $offset, gettype($value)));
        }
    }

    /**
     * Asserts value type for offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return mixed
     * @throws AttributeException
     */
    private function escapeValue($offset, $value)
    {
        if (!array_key_exists($offset, $this->escapedValues)) {
            return $value;
        }

        if (is_array($value)) {
            $value = array_walk_recursive($value, array($this, 'strip'));
        }

        return $this->strip($value, $this->escapedValues[$offset]);
    }

    /**
     * Strips string from invalid characters
     *
     * @param string $string string to strip
     * @param bool   $strict leaves only letters, numbers and separators in lowercase
     *
     * @return string
     */
    protected function strip($string, $strict = false)
    {
        $string = (string) $string;
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);

        if ($strict) {
            $string = strtolower($string);
            $string = preg_replace('#[^a-z0-9_\-]+#i', '_', $string);
        } else {
            $string = preg_replace('#[^a-z0-9_\-\[\]]+#i', '_', $string);
        }

        $string = trim($string, '_');

        return $string;
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
    public function set($offset, $value)
    {
        if ($value === null) {
            return $this;
        }

        $this->assertArrayValue($offset, $value);
        $this->storage[$offset] = $this->escapeValue($offset, $value);

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
     * @throws AttributeException
     */
    public function add($offset, $value)
    {
        if (!$this->isMultiValue($offset)) {
            $this->assertArrayValue($offset, $value);
            $this->set($offset, $value);

            return $this;
        }

        if (!is_scalar($value)) {
            throw new AttributeException(sprintf('Unable to add to attribute %s, only scalar values, got %s', $offset, gettype($value)));
        }

        $this->storage[$offset][] = $this->escapeValue($offset, $value);
        $this->storage[$offset] = array_unique($this->storage[$offset]);

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
            return $this->removeMultiValue($offset, $value);
        }

        unset($this->storage[$offset]);

        return $this;
    }

    /**
     * Removes value from offset
     *
     * @param string      $offset offset to remove from
     * @param null|string $value  value to remove
     *
     * @return $this
     */
    private function removeMultiValue($offset, $value)
    {
        if (!$this->isMultiValue($offset)) {
            return $this;
        }

        while (!null === $i = array_search($value, $this->storage[$offset])) {
            unset($this->storage[$offset][$i]);
        }

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
            $array = $this->prepareAttributes($array);

            foreach ($array as $offset => $value) {
                $this->assertArrayValue($offset, $value);
                $this->set($offset, $value);
            }
        }

        return $this->filter();
    }

    /**
     * Prepares attributes, changes them into key value pairs
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function prepareAttributes(array $attributes)
    {
        foreach ($attributes as $offset => $value) {
            if (!is_numeric($offset)) {
                continue;
            }

            unset($attributes[$offset]);
            $attributes[$value] = $value;
        }

        return $attributes;
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
        $this->set($offset, $value);
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
        return count($this->filter());
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
     * Returns attributes as string
     *
     * @param array $attributes
     *
     * @return string
     */
    public function render(array $attributes = array())
    {
        $storage = array_merge($this->storage, $attributes);

        $order = array('type', 'id', 'name', 'value', 'checked', 'selected', 'required');
        uksort(
            $storage,
            function ($a, $b) use ($order) {
                $a = array_search($a, $order);
                $b = array_search($b, $order);

                return ($a === false ? 999 : $a) - ($b === false ? 999 : $b);
            }
        );

        $attributes = array();
        foreach ($storage as $offset => $value) {
            if (empty($value)) {
                continue;
            }

            $attributes[$offset] = sprintf('%s="%s"', $offset, $this->isMultiValue($offset) ? implode(' ', $value) : $value);
        }

        return implode(' ', $attributes);
    }

    /**
     * Returns attributes as string
     *
     * @return string
     */
    function __toString()
    {
        return (string) $this->render();
    }
}
