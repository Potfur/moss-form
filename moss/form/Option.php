<?php
namespace moss\form;

use moss\form\AttributesBag;
use moss\form\OptionsBag;
use moss\form\Field;

/**
 * Option
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Option implements OptionInterface
{

    protected $identifier;
    protected $label;
    protected $value;
    protected $attributes;
    protected $options;

    /**
     * Creates option instance for form fields
     *
     * @param string      $label         option label
     * @param null|string $value         option value
     * @param array       $attributes    option attributes
     * @param array       $options       options
     */
    public function __construct($label, $value = null, $attributes = array(), $options = array())
    {
        $this->label($label);
        $this->value($value !== null ? $value : $label);
        $this->attributes = new AttributesBag($attributes);
        $this->options = new OptionsBag($options);
    }

    /**
     * Returns option identifier
     * If no identifier is set - new is generated, based on option name
     *
     * @param null|string $identifier option identifier
     *
     * @return string
     */
    public function identify($identifier = null)
    {
        if ($identifier) {
            $this->identifier = $this->strip($identifier, true);
        } elseif (!$this->identifier) {
            $this->identifier = $this->strip($this->value, true);
        }

        return $this->identifier;
    }

    /**
     * Sets field label
     *
     * @param string $label    field label
     *
     * @return Field
     */
    public function label($label = null)
    {
        if ($label !== null) {
            $this->label = $label;
        }

        return $this->label;
    }

    /**
     * Sets option value
     *
     * @param mixed $value
     *
     * @return Field
     */
    public function value($value = null)
    {
        if ($value !== null) {
            $this->value = htmlspecialchars($value);
        }

        return $this->value;
    }


    /**
     * Checks if field is visible
     * By default all fields are visible
     *
     * @return bool
     */
    public function isVisible()
    {
        return false;
    }

    /**
     * Returns attribute bag interface
     *
     * @return BagInterface
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Returns options bag interface
     *
     * @return BagInterface
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Strips string from invalid characters
     *
     * @param string $string    string to strip
     * @param bool   $lowercase if set to true, will return lowercase string
     *
     * @return string
     */
    private function strip($string, $lowercase = false)
    {
        $string = (string) $string;
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $string = preg_replace('#[^a-z0-9_\-\[\]]+#i', '_', $string);
        $string = trim($string, '_');

        if ($lowercase) {
            $string = strtolower($string);
        }

        return $string;
    }
}
