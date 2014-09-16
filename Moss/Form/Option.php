<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form;

use Moss\Form\Bag\AttributeBag;
use Moss\Form\Bag\OptionBag;
use Moss\Form\Field;

/**
 * Option
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Option implements OptionInterface
{

    protected $identifier;
    protected $label;
    protected $value;
    protected $attributes;
    protected $options;

    /**
     * Creates option instance for form fields
     *
     * @param string      $label      option label
     * @param null|string $value      option value
     * @param array       $attributes option attributes
     * @param array       $options    options
     */
    public function __construct($label, $value = null, $attributes = array(), $options = array())
    {
        $this->attributes = new AttributeBag($attributes);
        $this->options = new OptionBag($options);

        $this->label($label);
        $this->value($value);
    }

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
     * Returns attribute bag interface
     *
     * @return BagInterface
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Returns options bag interface
     *
     * @return BagInterface
     */
    public function options()
    {
        return $this->options;
    }
}
