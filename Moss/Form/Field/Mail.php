<?php
namespace Moss\Form\Field;

use Moss\Form\Field\Text;

/**
 * E-mail HTML5 form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Mail extends Text
{

    /**
     * Renders field
     *
     * @param mixed $value
     *
     * @return string
     */
    public function renderField($value = null)
    {
        return sprintf(
            '<input %s/>',
            $this->attributes->render(
                array(
                    'type' => 'email',
                    'label' => null,
                )
            )
        );
    }
}

