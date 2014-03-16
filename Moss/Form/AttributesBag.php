<?php
namespace Moss\Form;

/**
 * Form element attribute container
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class AttributesBag implements BagInterface
{

    private $reserved = array('id', 'type', 'name', 'value', 'checked', 'selected', 'required');
    private $arrays = array('class');
    private $attributes = array();

    /**
     * Constructor
     *
     * @param array $attributes
     * @param array $reserved
     */
    public function __construct($attributes = array(), $reserved = array('id', 'type', 'name', 'value', 'checked', 'selected', 'required'))
    {
        $this->reserved = $reserved;

        foreach ($attributes as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * Counts all elements in bag
     *
     * @return int
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * Retrieves elements attribute value
     *
     * @param string $name attribute name
     *
     * @return null|string
     */
    public function get($name = null)
    {
        if ($name === null) {
            return $this->all();
        }

        if (!isset($this->attributes[$name])) {
            return null;
        }

        if (in_array($name, $this->arrays)) {
            return $this->joinAttributeValue($this->attributes[$name]);
        }

        return $this->attributes[$name];
    }

    /**
     * Sets elements attribute
     * If attribute exists - overwrites it
     *
     * @param string      $name  attribute name
     * @param null|string $value attribute value
     *
     * @return $this
     * @throws AttributeException
     */
    public function set($name, $value = null)
    {
        if (!$this->isValidName($name)) {
            throw new AttributeException(sprintf('Invalid attribute name got "%s" is not valid or is reserved in field "%s"', is_object($name) ? get_class($name) : gettype($name), get_class($this)));
        }

        if ($this->isReserved($name)) {
            throw new AttributeException(sprintf('Attribute name "%s" is not valid or is reserved in field "%s"', $name, get_class($this)));
        }

        if (in_array($name, $this->arrays)) {
            $this->attributes[$name] = $this->splitAttributeValue($value);

            return $this;
        }

        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Adds value or values to elements attribute
     * Creates non existing attributes
     *
     * @param string       $name  attribute to add to
     * @param string|array $value value or array of values added
     *
     * @return $this
     * @throws AttributeException
     */
    public function add($name, $value)
    {
        if (!$this->isValidName($name)) {
            throw new AttributeException(sprintf('Invalid attribute name got "%s" is not valid or is reserved in field "%s"', is_object($name) ? get_class($name) : gettype($name), get_class($this)));
        }

        if ($this->isReserved($name)) {
            throw new AttributeException(sprintf('Attribute name "%s" is not valid or is reserved in field "%s"', $name, get_class($this)));
        }

        if (in_array($name, $this->arrays)) {
            if (!isset($this->attributes[$name])) {
                $this->attributes[$name] = array();
            }

            if (!is_array($value)) {
                $value = $this->splitAttributeValue($value);
            }

            $this->attributes[$name] = array_merge($this->attributes[$name], $value);
            $this->attributes[$name] = array_unique($this->attributes[$name]);

            return $this;
        }

        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Remove value or values from elements attribute
     *
     * @param string       $name  attribute to remove from
     * @param string|array $value value or array of values removed
     *
     * @return $this
     * @throws AttributeException
     */
    public function remove($name = null, $value = null)
    {
        if ($name === null && $value === null) {
            return $this->reset();
        }

        if (!$this->isValidName($name)) {
            throw new AttributeException(sprintf('Invalid attribute name got "%s" is not valid or is reserved in field "%s"', is_object($name) ? get_class($name) : gettype($name), get_class($this)));
        }

        if ($this->isReserved($name)) {
            throw new AttributeException(sprintf('Attribute name "%s" is not valid or is reserved in field "%s"', $name, get_class($this)));
        }

        if (!isset($this->attributes[$name])) {
            return $this;
        }

        if (in_array($name, $this->arrays) && $value !== null) {
            $value = array_diff($this->attributes[$name], (array) $value);
            $this->attributes[$name] = $value;

            return $this;
        }

        unset($this->attributes[$name]);

        return $this;
    }

    /**
     * Returns all attributes
     *
     * @return array
     */
    public function all()
    {
        $result = array();
        foreach ($this->attributes as $name => $values) {
            $result[$name] = in_array($name, $this->arrays) ? $this->joinAttributeValue($this->attributes[$name]) : $this->attributes[$name];
        }

        return $result;
    }

    /**
     * Removes all attributes in bag
     *
     * @return $this
     */
    public function reset()
    {
        $this->attributes = array();

        return $this;
    }


    /**
     * Returns true if attribute name is a valid one
     *
     * @param string $name
     *
     * @return bool
     */
    private function isValidName($name)
    {
        if (!is_scalar($name)) {
            return false;
        }

        if (!preg_match('/^[a-z]{1,}[a-z0-9-:]+$/', $name)) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if attribute is reserved
     *
     * @param string $name
     *
     * @return bool
     */
    private function isReserved($name)
    {
        return in_array($name, $this->reserved);
    }

    /**
     * Joins array of attribute values into string
     *
     * @param array  $value
     * @param string $separator
     *
     * @return string
     */
    private function joinAttributeValue(array $value, $separator = ' ')
    {
        $value = array_unique($value);

        return implode($separator, $value);
    }

    /**
     * Splits string of attribute values into array
     *
     * @param string $value
     * @param string $separator
     *
     * @return array
     */
    private function splitAttributeValue($value, $separator = ' ')
    {
        if (is_array($value)) {
            return $value;
        }

        return explode($separator, $value);
    }

    /**
     * Returns all not empty attributes as string
     *
     * @param array $additional
     *
     * @return string
     */
    public function toString($additional = array())
    {
        $attributes = array_merge($this->get(), (array) $additional);
        foreach ($attributes as $key => $value) {
            if (empty($value)) {
                unset($attributes[$key]);
                continue;
            }

            $attributes[$key] = sprintf('%s="%s"', $key, $value);
        }

        return implode(' ', $attributes);
    }
}