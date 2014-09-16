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

interface OptionInterface
{

    /**
     * Returns field identifier
     * If no identifier is set - new is generated, based on field name
     *
     * @param null|string $identifier field identifier
     *
     * @return string
     */
    public function identify($identifier = null);

    /**
     * Sets option label
     *
     * @param string $label
     *
     * @return string
     */
    public function label($label = null);

    /**
     * Sets option value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function value($value = null);

    /**
     * Returns attribute bag interface
     *
     * @return \Moss\Form\Bag\AttributeBag
     */
    public function attributes();

    /**
     * Returns options bag interface
     *
     * @return \Moss\Form\Bag\OptionBag
     */
    public function options();
}