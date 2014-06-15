<?php
namespace Moss\Form\Field;

/**
 * Input/Submit
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Submit extends Button
{

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<button %s>%s</button>',
            $this->attributes->render(array('type'=> 'submit', 'label' => null)),
            $this->attributes->get('label')
        );
    }
}
