<?php
namespace Moss\Form;

/**
 * Object oriented fieldset representation
 * Fieldset is represented as unordered lists
 * If fieldset is nested in existing form - list will be also nested
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Fieldset implements FieldsetInterface
{

    /** @var \Moss\Form\AttributeBag */
    protected $attributes;

    protected $tag = array(
        'group' => 'ul',
        'element' => 'li'
    );

    /** @var ElementInterface[] */
    protected $struct = array();

    /**
     * Constructor
     *
     * @param null  $label      fieldset label, if set - will be used as form field key
     * @param array $fields     array containing fields
     * @param array $attributes additional attributes as associative array
     */
    public function __construct($label = null, $fields = array(), $attributes = array())
    {
        $this->attributes = new AttributeBag($attributes);

        $this->label($label);

        foreach ($fields as $key => $field) {
            $this->set($key, $field);
        }
    }

    /**
     * Returns field identifier
     * If no identifier is set - new is generated, based on field name
     *
     * @param null|string $identifier field identifier
     *
     * @return string
     */
    public function identify($identifier = null)
    {
        if ($identifier) {
            $this->attributes->set('id', $identifier);
        } elseif (!$this->attributes->has('id')) {
            $this->attributes->set('id', $this->attributes->get('name'));
        }

        return $this->attributes->get('id');
    }

    /**
     * Sets field label
     *
     * @param string $label field label
     *
     * @return string
     */
    public function label($label = null)
    {
        if ($label !== null) {
            $this->attributes->set('label', $label);
        }

        return $this->attributes->get('label');
    }

    /**
     * Returns attribute bag interface
     *
     * @return \Moss\Form\AttributeBag
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Returns all error messages
     *
     * @return \Moss\Form\ErrorBag
     */
    public function errors()
    {
        $errors = new ErrorBag();
        foreach ($this->struct as $element) {
            if ($element instanceof FieldsetInterface || $element instanceof FieldInterface) {
                continue;
            }

            if (!$element->errors()
                ->has()
            ) {
                continue;
            }

            foreach ($element->errors()
                ->all() as $error) {
                $errors->add(null, $error);
            }
        }

        return $errors;
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
     * Checks if field is valid (if all conditions have been met)
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->struct as $Element) {
            if (!$Element->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns rendered and escaped fieldset
     *
     * @return string
     */
    public function prototype()
    {
        $str = $this->render();
        $str = htmlspecialchars($str);
        $str = str_replace(array("\r", "\n"), null, $str);

        return $str;
    }

    /**
     * Retrieves offset value
     *
     * @param string $offset
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($offset = null, $default = null)
    {
        if ($offset === null) {
            return $this->all();
        }

        return isset($this->struct[$offset]) ? $this->struct[$offset] : $default;
    }

    /**
     * Sets value to offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($offset, $value)
    {
        if ($offset === null) {
            array_push($this->struct, $value);

            return $this;
        }

        $this->struct[$offset] = $value;

        return $this;
    }

    /**
     * Returns true if offset exists in bag
     *
     * @param string $offset
     *
     * @return bool
     */
    public function has($offset = null)
    {
        if ($offset !== null) {
            return isset($this->struct[$offset]);
        }

        return $this->count() > 0;
    }

    /**
     * Adds value or values to offset
     * Creates offset if it does not exists
     *
     * @param string       $offset offset to add to
     * @param string|array $value  value or array of values added
     *
     * @return $this
     */
    public function add($offset, $value)
    {
        if ($offset === null) {
            $offset = count($offset);
        }

        $this->struct[$offset][] = $value;

        return $this;
    }


    /**
     * Removes offset from bag
     * If no offset set, removes all values
     *
     * @param string $offset offset to remove from
     *
     * @return $this
     */
    public function remove($offset = null)
    {
        if ($offset == null) {
            $this->struct = array();
        }

        if (!isset($this->struct[$offset])) {
            return $this;
        }

        unset($this->struct[$offset]);

        return $this;
    }

    /**
     * Returns all options
     * If array passed, becomes bag content
     *
     * @param array $array overwrites values
     *
     * @return array
     */
    public function all($array = array())
    {
        if ($array !== array()) {
            $this->struct = $array;
        }

        return $this->struct;
    }

    /**
     * Removes all options
     *
     * @return $this
     */
    public function reset()
    {
        $this->struct = array();

        return $this;
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     *
     * @return mixed Can return all value types.
     */
    public function &offsetGet($offset)
    {
        if (!isset($this->struct[$offset])) {
            $this->struct[$offset] = null;
        }

        return $this->struct[$offset];
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * Count elements of an object
     *
     * @return int
     */
    public function count()
    {
        return count($this->struct);
    }

    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->struct);
    }

    /**
     * Return the key of the current element
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->struct);
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        next($this->struct);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->struct);
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        $key = key($this->struct);

        if ($key === false || $key === null) {
            return false;
        }

        return isset($this->struct[$key]);
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        $nodes = array();

        $this->renderLabel($nodes);
        $this->renderHiddenFields($nodes);
        $this->renderVisibleFields($nodes);

        return implode("\n", $nodes);
    }

    /**
     * Renders label if set
     *
     * @param array $nodes
     */
    protected function renderLabel(&$nodes)
    {
        if ($this->attributes->get('label')) {
            $nodes[] = sprintf('<legend>%s</legend>', $this->attributes->get('label'));
        }
    }

    /**
     * Renders hidden fields
     *
     * @param array $nodes
     */
    protected function renderHiddenFields(&$nodes)
    {
        foreach ($this->struct as $field) {
            if ($field->isVisible() === true) {
                continue;
            }

            $nodes[] = (string) $field;
        }
    }

    /**
     * Renders visible fields with structure
     *
     * @param array $nodes
     * @param array $attributes
     */
    protected function renderVisibleFields(&$nodes, array $attributes = array())
    {
        $nodes[] = sprintf('<%s %s>', $this->tag['group'], $this->attributes->render($attributes));

        foreach ($this->struct as $field) {
            if ($field->isVisible() === false) {
                continue;
            }

            $nodes[] = sprintf('<%1$s>%2$s</%1s>', $this->tag['element'], (string) $field);
        }

        $nodes[] = sprintf('</%s>', $this->tag['group']);
    }

    /**
     * Returns error messages as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
