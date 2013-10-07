<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use \moss\form\Field;

/**
 * Plain text
 * Allows for text insertion into form structure
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Block extends Field {

	/**
	 * Constructor
	 *
	 * @param string      $text       field value
	 * @param array       $attributes additional attributes
	 */
	public function __construct($text = null, $attributes = array()) {
		$this->value($text);
        $this->attributes = new AttributesBag($attributes);
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
     * Renders label
     *
     * @return null
     */
    public function renderLabel() {
        return null;
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField() {
        return sprintf(
            '<p%2$s>%1$s</p>',
            $this->value(),
            $this->attributes()->toString()
        );
    }

    /**
     * Renders field errors
     *
     * @return null
     */
    public function renderError() {
        return null;
    }
}
