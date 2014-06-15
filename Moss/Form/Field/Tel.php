<?php
namespace Moss\Form\Field;

/**
 * Telephone form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Tel extends Text
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
                    'type' => 'tel',
                    'label' => null,
                )
            )
        );
    }
}

