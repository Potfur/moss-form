<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use \moss\form\field\Anchor;

/**
 * Cancel button
 * Implemented as link
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Cancel extends Anchor {

    /**
     * Constructor
     *
     * @param string $address url to redirect to
     * @param string $name name
     * @param array $attributes additional attributes
     */
    public function __construct($address, $name, $attributes = array()) {
        $this->name($name);
        $this->value($address);
        $this->attributes = new AttributesBag($attributes);
        $this->attributes->add('class', 'button cancel');
    }
}
