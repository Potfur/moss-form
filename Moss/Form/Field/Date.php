<?php
namespace Moss\Form\Field;

use Moss\Form\AttributesBag;
use Moss\Form\ErrorsBag;
use Moss\Form\Field;
use Moss\Form\ConditionException;

/**
 * Input/Text
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Date extends Field
{

    /** @var string */
    private $format = 'Y-m-d H:i:s';

    /** @var \DateTime */
    protected $value;

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param null   $label      field label
     * @param bool   $required   if true "required" tag will be inserted into label
     * @param array  $attributes additional attributes as associative array
     * @param string $format
     */
    public function __construct($name, $value = null, $label = null, $required = false, $attributes = array(), $format = null)
    {
        $this->name($name);
        $this->value($value);
        $this->label($label, $required);
        $this->required($required);
        $this->attributes = new AttributesBag($attributes);
        $this->errors = new ErrorsBag();

        $this->format($format);
    }

    /**
     * Sets date format
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format = null)
    {
        if ($format !== null) {
            $this->format = $format;
        }

        return $this->format;
    }

    /**
     * Sets field value
     *
     * @param mixed $value field value
     *
     * @return \DateTime
     */
    public function value($value = null)
    {
        if ($value !== null) {
            if (!$value instanceof \DateTime) {
                try {
                    $value = new \DateTime($value);
                } catch(\Exception $e) {
                    $value = new \DateTime('@' . (int) $value);
                }
            }

            $this->value = $value;
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

    /**
     * Validates the field by given condition
     * Condition can be: string (regular expression), array of values, function, closure or boolean
     *
     * @param string|array|callable $condition condition witch will be used
     * @param string                $message   error message if condition is not met
     * @param bool                  $force     if true, condition is checked even if not required and empty
     *
     * @return Field
     * @throws ConditionException
     */
    public function condition($condition, $message, $force = false)
    {
        if (!$force && !$this->required && $this->value === null) {
            return $this;
        }

        $value = $this->value->format($this->format);
        if (is_string($condition)) { // checks if condition is string (regexp)
            if (is_scalar($value) && !preg_match($condition, $value)) {
                $this
                    ->errors()
                    ->set($message);
            }
        } elseif (is_array($condition)) { // check if condition is array of permitted values
            if (!in_array($value, $condition)) {
                $this
                    ->errors()
                    ->set($message);
            }
        } elseif (is_callable($condition)) { // checks if condition is closure
            if (!$condition($value)) {
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
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<input type="datetime" name="%s" value="%s" id="%s" %s/>',
            $this->name(),
            $this->value ? $this->value->format($this->format) : null,
            $this->identify(),
            $this
                ->attributes()
                ->toString(array('required' => $this->required() ? 'required' : null))
        );
    }
}

