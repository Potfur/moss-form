<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form\Field;

use Moss\Form\Bag\AttributeBag;
use Moss\Form\Bag\ErrorBag;
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
    protected $format = 'Y-m-d H:i:s';

    /** @var \DateTime */
    protected $value;

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
                } catch (\Exception $e) {
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
     *
     * @return $this
     * @throws ConditionException
     */
    public function condition($condition, $message)
    {
        $this->conditions[] = array(
            'condition' => $condition,
            'message' => $message
        );

        return $this;
    }

    /**
     * Validates field
     *
     * @return $this
     */
    public function validate()
    {
        if (!$this->attributes->get('required') && $this->value === null) {
            return $this;
        }

        foreach ($this->conditions as $node) {
            if (!$this->validateValue($this->value->format($this->format), $node['condition'])) {
                $this->errors->add($node['message']);
            }
        }

        if ($this->errors->count()) {
            $this->attributes->add('class', 'error');
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
            '<input %s/>',
            $this->attributes->render(
                array(
                    'type' => 'datetime',
                    'label' => null,
                    'value' => $this->value ? $this->value->format($this->format) : null
                )
            )
        );
    }
}

