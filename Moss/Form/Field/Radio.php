<?php
namespace Moss\Form\Field;

use Moss\Form\AttributesBag;
use Moss\Form\ErrorsBag;
use Moss\Form\Field;
use Moss\Form\OptionInterface;
use Moss\Form\OptionsBag;

/**
 * Input/Checkbox
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Radio extends Field
{

    /** @var array|\Moss\Form\OptionsBag */
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
        $nodes[] = empty($options) ? $this->renderBlank() : $this->renderOptions($options);
        $nodes[] = sprintf('</%s>', $this->tag['group']);

        return implode("\n", $nodes);
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
     * Renders options
     *
     * @param array|OptionInterface[] $options
     *
     * @return null|string
     */
    protected function renderOptions(array $options)
    {
        if (empty($options)) {
            return null;
        }

        $nodes = array();
        foreach ($options as $Option) {
            $nodes[] = $this->renderOption($Option);
        }

        return implode("\n", $nodes);
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
                '<%1$s class="options">%2$s</%1$s>',
                $this->tag['group'],
                "\n" . $this->renderOptions($options)
            );
        }

        $field = sprintf(
            '<%1$s class="options"><input type="radio" name="%2$s" value="%4$s" id="%5$s" %6$s/><label for="%5$s" class="inline">%3$s</label>%7$s</%1$s>',
            $this->tag['element'],
            $this->name(),
            $Option->label(),
            $Option->value(),
            $id,
            $Option
                ->attributes()
                ->toString(array('checked' => $Option->value() == $this->value ? 'checked' : null)),
            $sub
        );

        return $field;
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
