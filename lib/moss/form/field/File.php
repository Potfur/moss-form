<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\Field;

/**
 * File form field
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class File extends Field {

	protected $marker = '@@@TAG@@@';
	protected $fields = array('name', 'type', 'tmp_name', 'error', 'size');

	/**
	 * Constructor
	 *
	 * @param string $name field name
	 * @param null $label field label
	 * @param bool $required if true "required" tag will be inserted into label
	 * @param array $attributes additional attributes as associative array
	 */
	public function __construct($name, $label = null, $required = false, $attributes = array()) {
		$this->name($name);
		$this->value($this->getFilesValue());
		$this->label($label, $required);
		$this->attributes = new AttributesBag($attributes);
	}

    /**
     * Sets field value
     *
     * @param mixed $value field value
     *
     * @return Field
     */
    public function value($value = null) {
        if($value !== null) {
            $this->value = (array) $value;
        }

        return $this->value;
    }

	/**
	 * Checks if field is visible
	 * By default all fields are visible
	 * @return bool
	 */
	public function isVisible() {
		return true;
	}


	/**
	 * Retrieves field value from $_FILES
	 * Returns array, where each element represents one file as array('name' => ..., 'type' => ..., 'tmp_name' => ..., 'error' => ..., 'size' => ...)
	 *
	 * @return array
	 */
	protected function getFilesValue() {
		if(empty($_FILES)) {
			return array();
		}

		$path = preg_replace('/^([^[]+)(.*)$/i', '[$1]['.$this->marker.']$2', $this->name);
		$path = preg_replace('/\[(.+)\]/imU', '[\'$1\']', $path);
		$path = str_replace('[]', null, $path);

		$result = array();
		$fnPrototype = ' return $files'.$path.';';
		foreach($this->fields as $field) {
			$fn = create_function('$files', str_replace($this->marker, $field, $fnPrototype));

			$node = $fn($_FILES);
			if(is_array($node)) {
				foreach($node as $i => $val) {
					$result[$i][$field] = $val;
				}
			}
			else {
				$result[0][$field] = $node;
			}
		}

		return $result;
	}

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField() {
        return sprintf(
            '<input type="file" name="%s" value="" id="%s" %s/>',
            $this->name(),
            $this->value(),
            $this->identify(),
            $this->attributes()->toString(array('required' => $this->required() ? 'required' : null))
        );
    }
}

