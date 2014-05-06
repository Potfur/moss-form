<?php
namespace Moss\Form\Field;

use Moss\Form\AttributeBag;
use Moss\Form\ErrorBag;
use Moss\Form\Field;

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
     * @param string $name       field name
     * @param null   $address    field value
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $address = null, array $attributes = array())
    {
        $this->attributes = new AttributeBag($attributes);
        $this->errors = new ErrorBag();

        $this->name($name);
        $this->value($address);

        if (!$this->attributes->has('label')) {
            $this->label($name);
        }

        if (!$this->attributes->has('id')) {
            $this->identify($name);
        }
    }

    /**
     * Sets field label
     *
     * @param string $label field label
     *
     * @return string
     */
    public function label($label = null)
    {
        if ($label !== null) {
            $this->attributes->set('label', $label);
        }

        return $this->attributes->get('label');
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
            $this->attributes->set('href', $value);
        }

        return $this->attributes->get('href');
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
     * Validates the field by given condition
     * Condition can be: string (regular expression), array of values, function, closure or boolean
     *
     * @param string|array|callable $condition condition witch will be used
     * @param string                $message   error message if condition is not met
     *
     * @return Field
     */
    public function condition($condition, $message)
    {
        return $this;
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
            '<a %s>%s</a>',
            $this->attributes->render(array('name' => null, 'label' => null)),
            $this->attributes->get('label')
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
