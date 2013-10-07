<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;

/**
 * Input/Text
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Date extends Field {

    /** @var string */
    private $format = 'Y-m-d H:i:s';

    /** @var \DateTime */
    protected $value;

	/**
	 * Constructor
	 *
	 * @param string $name       field name
	 * @param null   $value      field value
	 * @param null   $label      field label
	 * @param bool   $required   if true "required" tag will be inserted into label
	 * @param array  $attributes additional attributes as associative array
	 */
	public function __construct($name, $value = null, $label = null, $required = false, $attributes = array()) {
		$this->name($name);
		$this->value($value);
		$this->label($label, $required);
		$this->required($required);
		$this->attributes = new AttributesBag($attributes);
		$this->errors = new ErrorsBag();
	}

    /**
     * Sets date format
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format = null)
    {
        if ($format !== null) {
            $this->format = $format;
        }

        return $this->format;
    }

    /**
     * Sets field value
     *
     * @param mixed $value field value
     *
     * @return \DateTime
     */
    public function value($value = null)
    {
        if ($value !== null) {
            if (!$value instanceof \DateTime) {
                $value = new \DateTime($value);
            }

            $this->value = $value;
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
	 * Renders field
	 *
	 * @return string
	 */
	public function renderField() {
		return sprintf(
			'<input type="datetime" name="%s" value="%s" id="%s" %s/>',
			$this->name(),
			$this->value->format($this->format),
			$this->identify(),
			$this->attributes()->toString(array('required' => $this->required() ? 'required' : null))
		);
	}
}

