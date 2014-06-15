<?php
namespace Moss\Form\Field;

use Moss\Form\AttributeBag;
use Moss\Form\ConditionException;
use Moss\Form\ErrorBag;
use Moss\Form\Field;

/**
 * File form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class File extends Field
{

    protected $marker = '@@@TAG@@@';
    protected $fields = array('name', 'type', 'tmp_name', 'error', 'size');

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param array  $value      field value
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, array $value = array(), array $attributes = array())
    {
        $this->attributes = new AttributeBag($attributes, array('class', 'value'));
        $this->errors = new ErrorBag();

        $this->name($name);

        $this->attributes->set('value', array());
        $this->value($value !== array() ? $value : $this->getFilesValue());

        if (!$this->attributes->has('label')) {
            $this->label($name);
        }

        if (!$this->attributes->has('id')) {
            $this->identify($name);
        }
    }

    /**
     * Retrieves field value from $_FILES
     * Returns array, where each element represents one file as array('name' => ..., 'type' => ..., 'tmp_name' => ..., 'error' => ..., 'size' => ...)
     *
     * @return array
     */
    protected function getFilesValue()
    {
        if (empty($_FILES)) {
            return array();
        }

        $path = preg_replace('/^([^[]+)(.*)$/i', '[$1][' . $this->marker . ']$2', $this->attributes->get('name'));
        $path = preg_replace('/\[(.+)\]/imU', '[\'$1\']', $path);
        $path = str_replace('[]', null, $path);

        $result = array();
        $fnPrototype = ' return isset($files' . $path . ') ? $files' . $path . ' : null;';
        foreach ($this->fields as $field) {
            $fn = create_function('$files', str_replace($this->marker, $field, $fnPrototype));

            $node = $fn($_FILES);
            if (is_array($node)) {
                foreach ($node as $i => $val) {
                    $result[$i][$field] = $val;
                }
            } else {
                $result[0][$field] = $node;
            }
        }

        return $result;
    }

    /**
     * Sets field value
     *
     * @param array $value
     *
     * @return mixed
     */
    public function value($value = array())
    {
        if ($value !== array()) {
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
     * Returns true if value meets condition
     *
     * @param array $value
     * @param mixed $condition
     *
     * @return bool|int
     * @throws ConditionException
     */
    protected function validate($value, $condition)
    {
        if (is_string($condition)) { // checks if condition is string (regexp)
            $value = array_key_exists('type', $value) ? $value['type'] : null;
            if (!preg_match($condition, $value)) {
                return false;
            }
        } elseif (is_array($condition)) { // check if condition is array of permitted values
            $value = array_key_exists('type', $value) ? $value['type'] : null;
            if (!in_array($value, $condition)) {
                return false;
            }
        } elseif (is_callable($condition)) { // checks if condition is closure
            if (!$condition($value)) {
                return false;
            }
        } elseif (is_bool($condition)) { // checks boolean
            return $condition;
        } else {
            throw new ConditionException('Invalid condition for field "' . $this->attributes->get('name', 'unnamed') . '". Allowed condition types: regexp string, array of permitted values or closure');
        }

        return true;
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<input %s/>',
            $this->attributes->render(
                array(
                    'type' => 'file',
                    'label' => null,
                    'value' => array(),
                )
            )
        );
    }
}

