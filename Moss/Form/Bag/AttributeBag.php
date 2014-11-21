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
 * Field attribute bag
 *
 * @package  Moss Form
 * @author   Michal Wachowski <wachowski.michal@gmail.com>
 */
class AttributeBag extends AbstractBag implements BagInterface
{
    /**
     * List of multi valued attributes
     *
     * @var array
     */
    protected $multiValue = array(
        'class'
    );

    /**
     * List of escaped attributes where key is attribute name and value is if strict or not
     *
     * @var array
     */
    protected $escapedValues = array(
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
        $this->multiValue = $multiValue;
        $this->all($storage);
    }

    /**
     * Returns true if multi value offset
     *
     * @param string $offset
     *
     * @return bool
     */
    protected function isMultiValue($offset)
    {
        return in_array($offset, $this->multiValue);
    }

    /**
     * Asserts value type for offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @throws AttributeException
     */
    protected function assertArrayValue($offset, $value)
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
    protected function escapeValue($offset, $value)
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
                $this->set($key, $value);
            }

            return $this;
        }

        $this->assertArrayValue($offset, $value);
        $this->storage[$offset] = $this->escapeValue($offset, $value);

        return $this;
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

        if (is_array($value)) {
            foreach ($value as $node) {
                $this->storage[$offset][] = $this->escapeValue($offset, $node);
            }
        } else {
            $this->storage[$offset][] = $this->escapeValue($offset, $value);
        }

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
    protected function removeMultiValue($offset, $value)
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

        return $this->storage;
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
    public function __toString()
    {
        return (string) $this->render();
    }
}
