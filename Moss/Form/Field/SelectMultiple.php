<?php
namespace Moss\Form\Field;

use Moss\Form\Field\Checkbox;
use Moss\Form\OptionGroupInterface;
use Moss\Form\OptionInterface;

/**
 * Select form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class SelectMultiple extends Checkbox
{

    /**
     * Renders label
     *
     * @return string
     */
    public function renderLabel()
    {
        if (!$this->label()) {
            return null;
        }

        return sprintf(
            '<label for="%s">%s%s</label>',
            $this->identify(),
            $this->label(),
            $this->required() ? '<sup>*</sup>' : null
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
            '<select name="%s" id="%s" multiple="multiple" %s>',
            $this->name(),
            $this->identify(),
            $this
                ->attributes()
                ->toString(array('required' => $this->required() ? 'required' : null))
        );

        $options = $this->options->all();
        $nodes[] = empty($options) ? $this->renderBlank() : $this->renderOptions($options);
        $nodes[] = sprintf('</select>');

        return implode("\n", $nodes);
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
     *
     * @return string
     */
    protected function renderOption(OptionInterface $Option)
    {
        $id = $this->identify() . '_' . $Option->identify();

        $sub = null;
        $options = $Option
            ->options()
            ->all();

        if (count($options)) {
            $sub = sprintf(
                '<%1$s label="%2$s">%3$s</%1$s>',
                'optgroup',
                $Option->label(),
                $this->renderOptions($options)
            );
        }

        return sprintf(
            '<option value="%s" id="%s" %s>%s</option>%s',
            $Option->value(),
            $id,
            $Option
                ->attributes()
                ->toString(array('selected' => $Option->value() == $this->value ? 'selected' : null)),
            $Option->label(),
            $sub
        );
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
