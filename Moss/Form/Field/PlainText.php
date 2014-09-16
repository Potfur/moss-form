<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form\Field;

use Moss\Form\Bag\AttributeBag;
use Moss\Form\Bag\ErrorBag;
use Moss\Form\Field;

/**
 * Plain text
 * Allows for text insertion into form structure
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class PlainText extends Field
{
    private $tag;

    /**
     * Constructor
     *
     * @param string $text
     * @param array  $attributes
     * @param string $tag
     */
    public function __construct($text, $attributes = array(), $tag = 'p')
    {
        $this->attributes = new AttributeBag($attributes);
        $this->errors = new ErrorBag();
        $this->tag = $tag;

        $this->value($text);
    }

    /**
     * Checks if field is visible
     * By default all fields are visible
     *
     * @return bool
     */
    public function isVisible()
    {
        return true;
    }

    /**
     * Renders label
     *
     * @return null
     */
    public function renderLabel()
    {
        return null;
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<%1$s %3$s>%2$s</%1$s>',
            $this->tag,
            $this->value(),
            $this->attributes()
                ->render(array('value' => null))
        );
    }

    /**
     * Renders field errors
     *
     * @return null
     */
    public function renderError()
    {
        return null;
    }
}
