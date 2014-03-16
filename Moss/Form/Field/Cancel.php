<?php
namespace Moss\Form\Field;

use Moss\Form\AttributesBag;
use Moss\Form\ErrorsBag;
use Moss\Form\Field;

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
