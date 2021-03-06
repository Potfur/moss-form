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

use Moss\Form\ElementInterface;
use Moss\Form\AttributesBagInterface;

/**
 * Form field interface
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
interface FieldInterface extends ElementInterface
{

    /**
     * Sets field label
     *
     * @param string $label field label
     *
     * @return ElementInterface
     */
    public function label($label = null);

    /**
     * Sets field name
     *
     * @param string $name
     *
     * @return string
     */
    public function name($name = null);

    /**
     * Sets field value
     *
     * @param mixed $value field value
     *
     * @return mixed
     */
    public function value($value = null);

    /**
     * Sets field requirement
     * Returns true if field is required
     *
     * @param null $required
     *
     * @return bool
     */
    public function required($required = null);

    /**
     * Validates the field by given condition
     * Condition can be: string (regular expression), array of values or function or closure
     *
     * @param string|array|callable $condition condition witch will be used
     * @param string                $message   error message if condition is not met
     *
     * @return FieldInterface
     */
    public function condition($condition, $message);

    /**
     * Validates field
     *
     * @return $this
     */
    public function validate();

    /**
     * Returns attribute bag interface
     *
     * @return \Moss\Form\Bag\AttributeBag
     */
    public function attributes();

    /**
     * Renders label
     *
     * @return string
     */
    public function renderLabel();

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField();

    /**
     * Renders field errors
     *
     * @return string
     */
    public function renderError();
}
