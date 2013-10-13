<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;

/**
 * Cancel button
 * Implemented as link
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Cancel extends Anchor
{

    /**
     * Constructor
     *
     * @param string $name       name
     * @param string $address    url to redirect to
     * @param array  $attributes additional attributes
     */
    public function __construct($name, $address, $attributes = array())
    {
        $this->name($name);
        $this->value($address);
        $this->errors = new ErrorsBag();
        $this->attributes = new AttributesBag($attributes);
        $this->attributes->add('class', 'button cancel');
    }
}
