<?php
namespace Moss\Form\Field;

use Moss\Form\AttributesBag;
use Moss\Form\ErrorsBag;
use Moss\Form\Field;

/**
 * Plain div, text container
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Plain extends Field
{

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param string $value      field value
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, $attributes = array())
    {
        $this->name($name);
        $this->value($value);
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
     * Sets field value
     *
     * @param mixed $value field value
     *
     * @return mixed
     */
    public function value($value = null)
    {
        if ($value !== null) {
            $this->value = (string) $value;
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
            '<div id="%2$s" %3$s>%1$s</div>',
            $this->value(),
            $this->identify(),
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
