<?php
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
     * @return \Moss\Form\AttributeBag
     */
    public function attributes();

    /**
     * Returns options bag interface
     *
     * @return \Moss\Form\OptionBag
     */
    public function options();
}