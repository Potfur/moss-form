<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;

/**
 * Input/Text
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Text extends Field
{

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param null   $label      field label
     * @param bool   $required   if true "required" tag will be inserted into label
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, $label = null, $required = false, $attributes = array())
    {
        $this->name($name);
        $this->value($value);
        $this->label($label, $required);
        $this->required($required);
        $this->attributes = new AttributesBag($attributes);
        $this->errors = new ErrorsBag();
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

    /**
     * Renders field
     *
     * @param mixed $value
     *
     * @return string
     */
    public function renderField($value = null)
    {
        return sprintf(
            '<input type="text" name="%s" value="%s" id="%s" %s/>',
            $this->name(),
            $value === null ? $this->value() : $value,
            $this->identify(),
            $this
                ->attributes()
                ->toString(array('required' => $this->required() ? 'required' : null))
        );
    }
}

