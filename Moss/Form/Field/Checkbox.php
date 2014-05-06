<?php
namespace Moss\Form\Field;

use Moss\Form\AttributeBag;
use Moss\Form\ErrorBag;
use Moss\Form\Field;
use Moss\Form\OptionInterface;
use Moss\Form\OptionBag;
use Moss\Form\ConditionException;

/**
 * Checkbox form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Checkbox extends Field
{

    /** @var OptionBag */
    protected $options = array();

    private $tag = array(
        'group' => 'ul',
        'element' => 'li'
    );

    /**
     * Constructor
     *
     * @param string            $name       field name
     * @param null              $address    field value
     * @param array             $attributes additional attributes as associative array
     * @param OptionInterface[] $options    array of Option instances
     */
    public function __construct($name, $address = null, array $attributes = array(), $options = array())
    {
        $this->attributes = new AttributeBag($attributes, array('class', 'value'));
        $this->errors = new ErrorBag();
        $this->options = new OptionBag($options);

        $this->name($name);
        $this->value($address);

        if (!$this->attributes->has('label')) {
            $this->label($name);
        }

        if (!$this->attributes->has('id')) {
            $this->identify($name);
        }
    }

    /**
     * Sets field value
     *
     * @param array $value
     *
     * @return Checkbox
     */
    public function value($value = array())
    {
        if ($value !== array()) {
            $value = (array) $value;
            array_walk(
                $value, function (&$v) {
                    $v = htmlspecialchars($v);
                }
            );

            $this->attributes->set('value', $value);
        }

        return $this->attributes->get('value');
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
     * @return OptionBag
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Returns true if value meets condition
     *
     * @param $values
     * @param $condition
     *
     * @return bool|int
     * @throws ConditionException
     */
    protected function validate($values, $condition)
    {
        if (is_string($condition)) { // checks if condition is string (regexp)
            foreach ($values as $value) {
                if (!preg_match($condition, $value)) {
                    return false;
                }
            }
        } elseif (is_array($condition)) { // check if condition is array of permitted values
            foreach ($values as $value) {
                if (!in_array($value, $condition)) {
                    return false;
                }
            }
        } elseif (is_callable($condition)) { // checks if condition is closure
            foreach ($values as $value) {
                if (!$condition($value)) {
                    return false;
                }
            }
        } elseif (is_bool($condition)) { // checks boolean
            return $condition;
        } else {
            throw new ConditionException('Invalid condition for field "' . $this->attributes->get('name', 'unnamed') . '". Allowed condition types: regexp string, array of permitted values or closure');
        }

        return true;
    }

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
            '<span>%s%s</span>',
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
            '<%s %s>',
            $this->tag['group'],
            $this->attributes->render(array('name' => null, 'label' => null, 'value' => null, 'required' => null))
        );

        $options = $this->options->all();
        $nodes[] = empty($options) ? $this->renderBlank() : $this->renderOptions($options);
        $nodes[] = sprintf('</%s>', $this->tag['group']);

        return implode(PHP_EOL, $nodes);
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
     * @param int                     $i
     *
     * @return null|string
     */
    protected function renderOptions(array $options, &$i = 0)
    {
        if (empty($options)) {
            return null;
        }

        $nodes = array();
        foreach ($options as $option) {
            $nodes[] = $this->renderOption($option, $i);
        }

        return implode(PHP_EOL, $nodes);
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
            'type' => 'checkbox',
            'name' => $this->name() . '[]',
            'label' => null,
            'required' => $this->attributes->get('required') ? 'required' : null,
            'checked' => in_array($Option->value(), (array) $this->attributes->get('value')) ? 'checked' : null
        );

        $field = sprintf(
            '<input %1$s/><label for="%2$s" class="inline">%3$s</label>',
            $Option->attributes()
                ->render($attributes),
            $this->attributes->get('id'),
            $Option->label()
        );

        $sub = null;
        $options = $Option
            ->options()
            ->all();

        if (count($options)) {
            $sub = sprintf('<%1$s class="options">%2$s</%1$s>', $this->tag['group'], PHP_EOL . $this->renderOptions($options, $i));
        }

        $field = sprintf(
            '<%1$s class="options">%2$s%3$s</%1$s>',
            $this->tag['element'],
            $field,
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
