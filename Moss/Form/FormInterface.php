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
 * Form interface
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
interface FormInterface extends FieldsetInterface
{

    /**
     * Sets forms action
     *
     * @param string $action
     *
     * @return FormInterface
     */
    public function action($action = null);

    /**
     * Sets forms sending method
     *
     * @param string $method
     *
     * @return FormInterface
     */
    public function method($method = null);

    /**
     * Sets forms encoding type
     *
     * @param string $enctype
     *
     * @return FormInterface
     */
    public function enctype($enctype = null);
}
