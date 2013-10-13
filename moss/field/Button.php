<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;

/**
 * Button
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Button extends Field
{

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param string   $value      field value
     * @param string   $label      field label
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, $label = null, $attributes = array())
    {
        $this->name($name);
        $this->value($value);
        $this->label($label);
        $this->errors = new ErrorsBag();
        $this->attributes = new AttributesBag($attributes);
    }

    /**
     * Sets field name
     *
     * @param string $name
     *
     * @return $this
     */
    public function name($name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }

        return $this->name;
    }

    /**
     * Checks if field is visible
     * By default all fields are visible
     *
     * @return bool
     */
    public function isVisible()
    {
        return true;
    }

    public function condition($condition, $message)
    {
        return $this;
    }

    /**
     * Checks if field is valid (if all conditions have been met)
     *
     * @return bool
     */
    public function isValid()
    {
        return true;
    }

    /**
     * Renders label
     * Button has no label
     *
     * @return null
     */
    public function renderLabel()
    {
        return null;
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<button type="button" name="%1$s" value="%2$s" id="%3$s" %5$s>%4$s</button>',
            $this->name(),
            $this->value(),
            $this->identify(),
            $this->label(),
            $this
                ->attributes()
                ->toString()
        );
    }

    /**
     * Renders field errors
     * Button does not generate errors
     *
     * @return null
     */
    public function renderError()
    {
        return null;
    }
}
