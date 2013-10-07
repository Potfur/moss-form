<?php
namespace moss\form\field;

use \moss\form\field\Text;

/**
 * E-mail HTML5 form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Email extends Text {

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField() {
        return sprintf(
            '<input type="email" name="%s" value="%s" id="%s" %s/>',
            $this->name(),
            $this->value(),
            $this->identify(),
            $this->attributes()->toString(array('required' => $this->required() ? 'required' : null))
        );
    }
}

