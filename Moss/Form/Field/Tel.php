<?php
namespace Moss\Form\Field;

use Moss\Form\Field\Text;

/**
 * Tel HTML5 form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Tel extends Text
{

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<input type="tel" name="%s" value="%s" id="%s" %s/>',
            $this->name(),
            $this->value(),
            $this->identify(),
            $this
                ->attributes()
                ->toString(array('required' => $this->required() ? 'required' : null))
        );
    }
}

