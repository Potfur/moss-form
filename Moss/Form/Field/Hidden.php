<?php
namespace Moss\Form\Field;

use Moss\Form\AttributesBag;
use Moss\Form\ErrorsBag;
use Moss\Form\Field;

/**
 * Input/Hidden
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Hidden extends Field
{

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, $attributes = array())
    {
        $this->name($name);
        $this->value($value);
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
        return false;
    }

    /**
     * Renders label
     *
     * @return string
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
            '<input type="hidden" name="%s" value="%s" id="%s" %s/>',
            $this->name(),
            $this->value(),
            $this->identify(),
            $this
                ->attributes()
                ->toString()
        );
    }
}

