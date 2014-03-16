<?php
namespace Moss\Form\Field;

use Moss\Form\AttributesBag;
use Moss\Form\ErrorsBag;
use Moss\Form\Field;
use Moss\Form\OptionInterface;
use Moss\Form\OptionsBag;
use Moss\Form\ConditionException;

/**
 * Checkbox form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Checkbox extends Field
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
     * Sets field value
     *
     * @param array $value
     *
     * @return Checkbox
     */
    public function value($value = null)
    {
        if ($value === null) {
            return $this->value;
        }

        $this->value = (array) $value;
        array_walk(
            $this->value, function (&$v) {
                $v = htmlspecialchars($v);
            }
        );

        return $this->value;
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
     * Validates the field by given condition
     * Condition can be: string (regular expression), array of values, function, closure or boolean
     *
     * @param string|array|callable $condition condition witch will be used
     * @param string                $message   error message if condition is not met
     * @param bool                  $force     if true, condition is checked even if not required and empty
     *
     * @return Field
     * @throws ConditionException
     */
    public function condition($condition, $message, $force = false)
    {
        if (!$force && !$this->required && $this->value === null) {
            return $this;
        }

        if (is_string($condition)) { // checks if condition is string (regexp)
            foreach ($this->value as $value) {
                if (is_scalar($value) && !preg_match($condition, $value)) {
                    $this->errors->set($message);
                    break;
                }
            }
        } elseif (is_array($condition)) { // check if condition is array of permitted values
            foreach ($this->value as $value) {
                if (!in_array($value, $condition)) {
                    $this->errors->set($message);
                    break;
                }
            }
        } elseif (is_callable($condition)) { // checks if condition is closure
            foreach ($this->value as $value) {
                if (!$condition($value)) {
                    $this->errors->set($message);
                    break;
                }
            }
        } elseif (is_bool($condition)) { // checks boolean
            if (!$condition) {
                $this->errors->set($message);
            }
        } else {
            throw new ConditionException('Invalid condition for field "' . $this->name . '". Allowed condition types: regexp string, array of permitted values or closure');
        }

        return $this;
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
            '<%1$s><input type="checkbox" name="%2$s[]" value="" id="%3$s"/><label for="%3$s" class="inline">---</label></%1$s>',
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

        $attributes = array(
            'required' => $this->required() && count($this->options) == 1,
            'checked' => in_array($Option->value(), (array) $this->value) ? 'checked' : null
        );

        $field = sprintf(
            '<%1$s class="options"><input type="checkbox" name="%2$s[]" value="%4$s" id="%5$s" %6$s/><label for="%5$s" class="inline">%3$s%7$s</label>%8$s</%1$s>',
            $this->tag['element'],
            $this->name(),
            $Option->label(),
            $Option->value(),
            $id,
            $Option
                ->attributes()
                ->toString($attributes),
            $attributes['required'] ? '<sup>*</sup>' : null,
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

    /**
     * Returns prototype string (for javascript templates)
     *
     * @return string
     */
    public function prototype()
    {
        return $this->renderLabel() . $this->renderError() . $this->renderField();
    }
}
