<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;
use moss\form\OptionInterface;
use moss\form\OptionsBag;

/**
 * Input/Checkbox
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Radio extends Field
{

    /** @var array|\moss\form\OptionsBag */
    protected $options = array();

    private $tag = array(
        'group' => 'ul',
        'element' => 'li'
    );

    /**
     * Constructor
     *
     * @param string                  $name       field name
     * @param null|string             $value      field value (checked values)
     * @param null|string             $label      field label
     * @param bool                    $required   if true "required" tag will be inserted into label
     * @param array                   $attributes additional attributes as associative array
     * @param array|OptionInterface[] $options    array of Option instances
     */
    public function __construct($name, $value = null, $label = null, $required = false, $attributes = array(), $options = array())
    {
        $this->name($name);
        $this->value($value);
        $this->label($label);
        $this->required($required);
        $this->attributes = new AttributesBag($attributes);
        $this->errors = new ErrorsBag();
        $this->options = new OptionsBag($options);
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
     * Returns options bag interface
     *
     * @return OptionsBag
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Renders label
     *
     * @return string
     */
    public function renderLabel()
    {
        if (!$this->label) {
            return null;
        }

        if (count($this->options) == 1) {
            return parent::renderLabel();
        }

        return sprintf(
            '<span>%s</span>',
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
            '<%s %s>', $this->tag['group'], $this
                ->attributes()
                ->toString(array('id' => $this->identify()))
        );

        $options = $this->options->all();
        if (empty($options)) {
            $nodes[] = $this->renderBlank();
        } else {
            foreach ($options as $Option) {
                $nodes[] = $this->renderOption($Option);
            }
        }

        $nodes[] = sprintf('</%s>', $this->tag['group']);

        return implode("\n", $nodes);
    }

    /**
     * @param array                   $nodes
     * @param array|OptionInterface[] $options
     * @param bool                    $blank
     *
     * @return array
     */
    protected function renderOptions($nodes, $options = array(), $blank = false)
    {
        if (empty($options)) {
            if ($blank) {
                $nodes[] = $this->renderBlank();
            }

            return $nodes;
        }

        foreach ($options as $Option) {
            $nodes[] = $this->renderOption($Option);
        }

        return $nodes;
    }

    /**
     * Renders blank option
     *
     * @return string
     */
    protected function renderBlank()
    {
        return sprintf(
            '<%1$s><input type="radio" name="%2$s" value="" id="%3$s"/><label for="%3$s" class="inline">---</label></%1$s>',
            $this->tag['element'],
            $this->name(),
            $this->identify() . '_empty'
        );
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

        $field = sprintf(
            '<%1$s class="options"><input type="radio" name="%2$s" value="%4$s" id="%5$s" %6$s/><label for="%5$s" class="inline">%3$s</label>%7$s</%1$s>',
            $this->tag['element'],
            $this->name(),
            $Option->label(),
            $Option->value(),
            $id,
            $Option
                ->attributes()
                ->toString(array('checked' => $Option->value() == $this->value() ? 'checked' : null)),
            $this->renderSubOptions($Option)
        );

        return $field;
    }

    /**
     * Renders subn options
     *
     * @param OptionInterface $Option
     *
     * @return null|string
     */
    protected function renderSubOptions(OptionInterface $Option)
    {
        $subOptions = $Option
            ->options()
            ->all();

        if (empty($subOptions)) {
            return null;
        }

        $nodes = array();
        foreach ($subOptions as $subOption) {
            $nodes[] = $this->renderOption($subOption);
        }

        return sprintf('<%1$s class="options">%2$s</%1$s>', $this->tag['group'], "\n" . implode("\n", $nodes));
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        return $this->renderLabel() . $this->renderError() . $this->renderField();
    }
}
