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

/**
 * Form element interface inherited by fields and fieldsets
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
interface ElementInterface
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
     * Checks if field is visible
     * By default all fields are visible
     *
     * @return bool
     */
    public function isVisible();

    /**
     * Checks if field is valid (if all conditions have been met)
     *
     * @return bool
     */
    public function isValid();

    /**
     * Returns all error messages
     *
     * @return \Moss\Form\Bag\ErrorBag
     */
    public function errors();

    /**
     * Renders element
     *
     * @return string
     */
    public function render();

    /**
     * Casts element to string
     *
     * @return string
     */
    public function __toString();
}
