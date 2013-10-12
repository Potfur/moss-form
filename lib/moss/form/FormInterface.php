<?php
namespace moss\form;

use moss\form\FieldsetInterface;

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
