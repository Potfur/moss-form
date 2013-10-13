<?php
namespace moss\form;

use moss\form\FieldInterface;
use moss\form\ConditionException;

/**
 * Abstract form field prototype
 *
 * @package moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
abstract class Field implements FieldInterface
{
    /** @var \moss\form\AttributesBag */
    protected $attributes;

    /** @var \moss\form\ErrorsBag */
    protected $errors;

    protected $identifier;

    protected $label;
    protected $required;

    protected $name;
    protected $value;

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
            $this->identifier = $this->strip($identifier, true);
        } elseif (!$this->identifier) {
            $this->identifier = $this->strip($this->name, true);
        }

        return $this->identifier;
    }

    /**
     * Sets field label
     *
     * @param string $label    field label
     *
     * @return string
     */
    public function label($label = null)
    {
        if ($label !== null) {
            $this->label = $label;
        }

        return $this->label;
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
            $this->name = $this->strip($name, false);
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
            $this->value = htmlspecialchars($value);
        }

        return $this->value;
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
            $this->required = (bool) $required;
        }

        return $this->required;
    }

    /**
     * Validates the field by given condition
     * Condition can be: string (regular expression), array of values, function, closure or boolean
     *
     * @param string|array|callable $condition condition witch will be used
     * @param string                $message   error message if condition is not met
     *
     * @return Field
     * @throws ConditionException
     */
    public function condition($condition, $message)
    {
        if (!$this->required && $this->value === null) {
            return $this;
        }

        if (is_string($condition)) { // checks if condition is string (regexp)
            if (is_scalar($this->value) && !preg_match($condition, $this->value)) {
                $this
                    ->errors()
                    ->set($message);
            }
        } elseif (is_array($condition)) { // check if condition is array of permitted values
            if (!in_array($this->value, $condition)) {
                $this
                    ->errors()
                    ->set($message);
            }
        } elseif (is_callable($condition)) { // checks if condition is closure
            if (!$condition($this->value)) {
                $this
                    ->errors()
                    ->set($message);
            }
        } elseif (is_bool($condition)) { // checks boolean
            if (!$condition) {
                $this
                    ->errors()
                    ->set($message);
            }
        } else {
            throw new ConditionException('Invalid condition for field "' . $this->name . '". Allowed condition types: regexp string, array of permitted values or closure');
        }

        $count = $this
            ->errors()
            ->count();

        if ($count) {
            $this
                ->attributes()
                ->add('class', 'error');
        }

        return $this;
    }

    /**
     * Returns attribute bag interface
     *
     * @return AttributesBag
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Checks if field is visible
     * By default all fields are visible
     *
     * @return bool
     */
    abstract public function isVisible();

    /**
     * Checks if field is valid (if all conditions have been met)
     *
     * @return bool
     */
    public function isValid()
    {
        if (!$this->errors()) {
            return true;
        }

        return $this
            ->errors()
            ->count() === 0;
    }

    /**
     * Returns all error messages
     *
     * @return ErrorsBag
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
     * Renders field
     *
     * @return string
     */
    abstract public function renderField();

    /**
     * Renders field errors
     *
     * @return string
     */
    public function renderError()
    {
        return $this
            ->errors()
            ->toString();
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

    /**
     * Strips string from invalid characters
     *
     * @param string $string     string to strip
     * @param bool   $identifier if set to true, will return lowercase string
     *
     * @return string
     */
    private function strip($string, $identifier = false)
    {
        $string = (string) $string;
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);

        if ($identifier) {
            $string = strtolower($string);
            $string = preg_replace('#[^a-z0-9_\-]+#i', '_', $string);
        } else {
            $string = preg_replace('#[^a-z0-9_\-\[\]]+#i', '_', $string);
        }

        $string = trim($string, '_');

        return $string;
    }
}
