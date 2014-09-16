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

use Moss\Form\OptionInterface;

/**
 * Select form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Select extends Radio
{

    /**
     * Renders label
     *
     * @return string
     */
    public function renderLabel()
    {
        if (!$this->attributes->has('label') || ($this->options->count() == 1)) {
            return null;
        }

        return sprintf(
            '<label for="%s">%s%s</label>',
            $this->attributes->get('id'),
            $this->attributes->get('label'),
            $this->attributes->get('required') ? '<sup>*</sup>' : null
        );
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        $nodes = array();

        $nodes[] = sprintf(
            '<select %s>',
            $this
                ->attributes()
                ->render(array('label' => null, 'value' => null))
        );

        $options = $this->options->all();
        $nodes[] = empty($options) ? $this->renderBlank() : $this->renderOptions($options);
        $nodes[] = sprintf('</select>');

        return implode(PHP_EOL, $nodes);
    }

    /**
     * Renders blank option
     *
     * @return string
     */
    protected function renderBlank()
    {
        return sprintf('<option value="" id="%s">---</option>', $this->identify() . '_empty');
    }

    /**
     * Renders single checkbox button
     *
     * @param OptionInterface $Option
     * @param int             $i
     *
     * @return string
     */
    protected function renderOption(OptionInterface $Option, &$i)
    {
        $attributes = array(
            'id' => $Option->identify() ? $Option->identify() : $this->identify() . '_' . $i++,
            'label' => null,
            'selected' => $Option->value() == $this->attributes->get('value') ? 'selected' : null
        );

        $field = sprintf(
            '<option %s/>%s</option>',
            $Option->attributes()
                ->render($attributes),
            $Option->label()
        );

        $sub = null;
        $options = $Option
            ->options()
            ->all();

        if (count($options)) {
            $sub = sprintf('<optgroup label="%s">%s</optgroup>', $Option->label(), $this->renderOptions($options));
            $field .= $sub;
        }

        return $field;
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        return $this->renderLabel() . $this->renderField() . $this->renderError();
    }
}
