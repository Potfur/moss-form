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

use Moss\Form\Bag\BagInterface;

/**
 * Fieldset interface
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
interface FieldsetInterface extends ElementInterface, BagInterface
{

    /**
     * Sets fieldset label
     *
     * @param string $label field label
     *
     * @return string
     */
    public function label($label = null);

    /**
     * Returns attribute bag interface
     *
     * @return \Moss\Form\AttributeAbstractBag
     */
    public function attributes();

    /**
     * Returns rendered and escaped fieldset
     *
     * @return string
     */
    public function prototype();
}
