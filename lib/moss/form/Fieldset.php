<?php
namespace moss\form;

use moss\form\AttributesBag;
use moss\form\FieldException;
use moss\form\FieldsetInterface;
use moss\form\ElementInterface;

/**
 * Object oriented fieldset representation
 * Fieldset is represented as unordered lists
 * If fieldset is nested in existing form - list will be also nested
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Fieldset implements FieldsetInterface {

	/** @var \moss\form\AttributesBag */
	protected $attributes;

    /** @var \moss\form\ErrorsBag */
    protected $errors;

	protected $identifier;

	protected $label;
	protected $valid;
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
	public function __construct($label = null, $fields = array(), $attributes = array()) {
		$this->label($label);
		$this->setFields($fields);
		$this->attributes = new AttributesBag($attributes);
        $this->errors = new ErrorsBag();
	}

	/**
	 * Sets field label
	 *
	 * @param string $label    field label
	 *
	 * @return string
	 */
	public function label($label = null) {
		if($label !== null) {
			$this->label = $label;
		}

		return $this->label;
	}

	/**
	 * Returns attribute bag interface
	 *
	 * @return AttributesBag
	 */
	public function attributes() {
		return $this->attributes;
	}

	/**
	 * Sets fieldset fields
	 * Fields must be passed as array key - value pairs, where key is field/fieldsets identifier
	 *
	 * @param array $fields array containing fields
	 *
	 * @return $this
	 */
	public function setFields(array $fields) {
		foreach($fields as $id => $field) {
			$this->setField($id, $field);
		}

		return $this;
	}

	/**
	 * Returns all element attributes as array
	 *
	 * @return array|ElementInterface[]
	 */
	public function getFields() {
		return $this->struct;
	}


	/**
	 * Adds element to fieldset
	 *
	 * @param string           $identifier
	 * @param ElementInterface $Element
	 *
	 * @return $this
	 */
	public function setField($identifier, ElementInterface $Element) {
		$this->struct[$identifier] = $Element;

		return $this;
	}

	/**
	 * Returns element from fieldset
	 *
	 * @param string $identifier
	 *
	 * @return mixed|ElementInterface
	 * @throws FieldException
	 */
	public function getField($identifier) {
		if(!isset($this->struct[$identifier])) {
			throw new FieldException(sprintf('Invalid identifier or field "%s" does not exists in fieldset', $identifier));
		}

		return $this->struct[$identifier];
	}

	/**
	 * Removes element from fieldset
	 *
	 * @param string $identifier
	 *
	 * @return FieldsetInterface
	 */
	public function removeField($identifier) {
		if(isset($this->struct[$identifier])) {
			unset($this->struct[$identifier]);
		}

		return $this;
	}

	/**
	 * Returns field identifier
	 * If no identifier is set - new is generated, based on field name
	 *
	 * @param null|string $identifier field identifier
	 *
	 * @return string
	 */
	public function identify($identifier = null) {
		if($identifier) {
			$this->identifier = $this->strip($identifier, true);
		}
		elseif(!$this->identifier) {
			$this->identifier = $this->strip($this->label, true);
		}

		return $this->identifier;
	}

	/**
	 * Checks if field is visible
	 * By default all fields are visible
	 *
	 * @return bool
	 */
	public function isVisible() {
		return true;
	}

	/**
	 * Checks if field is valid (if all conditions have been met)
	 *
	 * @return bool
	 */
	public function isValid() {
		foreach($this->struct as $Element) {
			if(!$Element->isValid()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns all error messages
	 *
	 * @return ErrorsBag
	 */
	public function errors() {
		return $this->errors;
	}

	/**
	 * Renders element
	 *
	 * @return string
	 */
	public function render() {
		$nodes = array();

		foreach($this->renderFields(false) as $field) {
			$nodes[] = $field;
		}

		$attr = $this
			->attributes()
			->toString();

		$nodes[] = sprintf('<%s %s>', $this->tag['group'], $attr);

		foreach($this->renderFields(true) as $field) {
			$nodes[] = sprintf('<%1$s>%2$s</%1s>', $this->tag['element'], $field);
		}

		$nodes[] = sprintf('</%s>', $this->tag['group']);

		return implode("\n", $nodes);
	}

	/**
	 * Returns array of rendered fields matching set visibility
	 *
	 * @param bool $visible
	 *
	 * @return array
	 */
	protected function renderFields($visible) {
		$nodes = array();

		foreach($this->struct as $Element) {
			if($Element->isVisible() === !$visible) {
				continue;
			}

			$nodes[] = $Element->render();
		}

		return $nodes;
	}

	/**
	 * Casts element to string
	 *
	 * @return string
	 */
	public function __toString() {
        try {
		    return $this->render();
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
	}

	/**
	 * Checks if offset exists
	 *
	 * @param int|string $offset offset to check
	 *
	 * @return bool
	 */
	public function offsetExists($offset) {
		return isset($this->struct[$offset]);
	}

	/**
	 * Retrieves offset value
	 *
	 * @param int|string $offset offset to retrieve
	 *
	 * @return ElementInterface
	 */
	public function &offsetGet($offset) {
		if(!isset($this->struct[$offset])) {
			$this->struct[$offset] = null;
		}

		return $this->struct[$offset];
	}

	/**
	 * Sets value for offset
	 *
	 * @param int|string $offset offset to set
	 * @param mixed      $value  offsets value
	 */
	public function offsetSet($offset, $value) {
		if($offset === null) {
			$offset = count($this->struct);
		}

		$this->struct[$offset] = $value;
	}

	/**
	 * Unsets offset
	 *
	 * @param int|string $offset offset to unset
	 */
	public function offsetUnset($offset) {
		unset($this->struct[$offset]);
	}

	/**
	 * Return the current element
	 *
	 * @return ElementInterface
	 */
	public function current() {
		return current($this->struct);
	}

	/**
	 * Move forward to next element
	 */
	public function next() {
		next($this->struct);
	}

	/**
	 * Return the key of the current element
	 *
	 * @return mixed
	 */
	public function key() {
		return key($this->struct);
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return boolean
	 */
	public function valid() {
		$key = key($this->struct);

		while($key !== null) {
			$this->next();
			$key = key($this->struct);
		}

		if($key === false || $key === null) {
			return false;
		}

		return isset($this->struct[$key]);
	}

	/**
	 * Rewind the Iterator to the first element
	 */
	public function rewind() {
		reset($this->struct);
	}

	/**
	 * Count elements of an object
	 *
	 * @return int
	 */
	public function count() {
		return count($this->struct);
	}

	/**
	 * Strips string from invalid characters
	 *
	 * @param string $string    string to strip
	 * @param bool   $lowercase if set to true, will return lowercase string
	 *
	 * @return string
	 */
	private function strip($string, $lowercase = false) {
		$string = (string) $string;
		$string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
		$string = preg_replace('#[^a-z0-9_\-\[\]]+#i', '_', $string);
		$string = trim($string, '_');

		if($lowercase) {
			$string = strtolower($string);
		}

		return $string;
	}
}
