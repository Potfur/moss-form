<?php
namespace Moss\Form;

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
     * @return \Moss\Form\AttributeBag
     */
    public function attributes();

    /**
     * Returns rendered and escaped fieldset
     *
     * @return string
     */
    public function prototype();
}
