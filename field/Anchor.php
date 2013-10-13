<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;

/**
 * Link
 * Allows for link insertion in form structure
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Anchor extends Field
{

    /**
     * Constructor
     *
     * @param string $name       name
     * @param string $address    url to redirect to
     * @param array  $attributes additional attributes
     */
    public function __construct($name, $address, $attributes = array())
    {
        $this->name($name);
        $this->value($address);
        $this->errors = new ErrorsBag();
        $this->attributes = new AttributesBag($attributes);
        $this->attributes->add('class', 'button');
    }

    /**
     * Sets field name
     *
     * @param string $name
     *
     * @return string
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


    /**
     * Renders label
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
            '<a href="%2$s" id="%3$s" %4$s>%1$s</a>',
            $this->name(),
            $this->value(),
            $this->identify(),
            $this
                ->attributes()
                ->toString()
        );
    }

    /**
     * Renders field errors
     *
     * @return null
     */
    public function renderError()
    {
        return null;
    }

}
