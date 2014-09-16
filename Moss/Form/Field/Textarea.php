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
 * Textarea (multiline text field)
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Textarea extends Field
{

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, array $attributes = array())
    {
        $this->attributes = new AttributeBag(array_merge(array('cols' => 20, 'rows' => 10), $attributes));
        $this->errors = new ErrorBag();

        $this->name($name);
        $this->value($value);

        if (!$this->attributes->has('label')) {
            $this->label($name);
        }

        if (!$this->attributes->has('id')) {
            $this->identify($name);
        }
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
     * Sets field width in columns
     *
     * @param int $cols number of columns
     *
     * @return int
     */
    public function cols($cols = null)
    {
        if ($cols !== null) {
            $this->attributes->set('cols', $cols);
        }

        return $this->attributes->get('cols');
    }

    /**
     * Sets field height in rows
     *
     * @param int $rows number of rows
     *
     * @return int
     */
    public function rows($rows = null)
    {
        if ($rows !== null) {
            $this->attributes->set('rows', $rows);
        }

        return $this->attributes->get('rows');
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<textarea %s>%s</textarea>',
            $this
                ->attributes()
                ->render(array('label' => null, 'value' => null)),
            $this->value()
        );
    }
}
