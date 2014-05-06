<?php
namespace Moss\Form;

/**
 * Abstract form field prototype
 *
 * @package moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
abstract class Field implements FieldInterface
{
    /**
     * @var \Moss\Form\AttributeBag
     */
    protected $attributes;

    /**
     * @var \Moss\Form\ErrorBag
     */
    protected $errors;

    /**
     * Returns field identifier
     * If no identifier is set - new is generated, based on field name
     *
     * @param null|string $identifier field identifier
     *
     * @return string
     */
    public function identify($identifier = null)
    {
        if ($identifier) {
            $this->attributes->set('id', $identifier);
        } elseif (!$this->attributes->has('id')) {
            $this->attributes->set('id', $this->attributes->get('name'));
        }

        return $this->attributes->get('id');
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
     * Sets field name
     *
     * @param string $name
     *
     * @return string
     */
    public function name($name = null)
    {
        if ($name !== null) {
            $this->attributes->set('name', $name);
        }

        return $this->attributes->get('name');
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
            $this->attributes->set('value', $value);
        }

        return $this->attributes->get('value');
    }

    /**
     * Sets field requirement
     * Returns true if field is required
     *
     * @param null $required
     *
     * @return bool
     */
    public function required($required = null)
    {
        if ($required !== null) {
            $this->attributes->set('required', (bool) $required);
        }

        return $this->attributes->get('required');
    }

    /**
     * Validates the field by given condition
     * Condition can be: string (regular expression), array of values, function, closure or boolean
     *
     * @param string|array|callable $condition condition witch will be used
     * @param string                $message   error message if condition is not met
     *
     * @return $this
     * @throws ConditionException
     */
    public function condition($condition, $message)
    {
        if (!$this->attributes->get('required') && $this->attributes->get('value') === null) {
            return $this;
        }

        if (!$this->validate($this->attributes->get('value'), $condition)) {
            $this->errors->add(null, $message);
        }

        $count = $this->errors->count();

        if ($count) {
            $this->attributes->add('class', 'error');
        }

        return $this;
    }

    /**
     * Returns true if value meets condition
     *
     * @param $value
     * @param $condition
     *
     * @return bool|int
     * @throws ConditionException
     */
    protected function validate($value, $condition)
    {
        if (is_bool($condition)) { // checks boolean
            return $condition;
        } elseif (is_scalar($condition)) { // checks if condition is string (regexp)
            return preg_match($condition, $value);
        } elseif (is_array($condition)) { // check if condition is array of permitted values
            return in_array($value, $condition);
        } elseif (is_callable($condition)) { // checks if condition is closure
            return $condition($value);
        } else {
            throw new ConditionException('Invalid condition for field "' . $this->attributes->get('name', 'unnamed') . '". Allowed condition types: regexp string, array of permitted values or closure');
        }
    }

    /**
     * Returns attribute bag interface
     *
     * @return \Moss\Form\AttributeBag
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Checks if field is valid (if all conditions have been met)
     *
     * @return bool
     */
    public function isValid()
    {
        return !$this->errors->has();
    }

    /**
     * Returns all error messages
     *
     * @return \Moss\Form\ErrorBag
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Renders label
     *
     * @return string
     */
    public function renderLabel()
    {
        if (!$this->label()) {
            return null;
        }

        return sprintf(
            '<label for="%s">%s%s</label>',
            $this->identify(),
            $this->label(),
            $this->required() ? '<sup>*</sup>' : null
        );
    }

    /**
     * Renders field errors
     *
     * @return string
     */
    public function renderError()
    {
        return (string) $this->errors;
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        return $this->renderLabel() . $this->renderField() . $this->renderError();
    }

    /**
     * Casts element to string
     *
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->render();
    }
}
