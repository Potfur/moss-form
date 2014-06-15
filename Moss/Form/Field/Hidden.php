<?php
namespace Moss\Form\Field;

use Moss\Form\AttributeBag;
use Moss\Form\ErrorBag;
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
    public function __construct($name, $value = null, array $attributes = array())
    {
        $this->attributes = new AttributeBag($attributes);
        $this->errors = new ErrorBag();

        $this->name($name);
        $this->value($value);

        if (!$this->attributes->has('label')) {
            $this->label($name);
        }

        if (!$this->attributes->has('id')) {
            $this->identify($name);
        }
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
     * @param mixed $value
     *
     * @return string
     */
    public function renderField($value = null)
    {
        return sprintf(
            '<input %s/>',
            $this->attributes->render(
                array(
                    'type' => 'hidden',
                    'label' => null,
                )
            )
        );
    }
}

