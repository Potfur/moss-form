<?php
namespace moss\form\field;

use \moss\form\field\Button;

/**
 * Input/Submit
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Submit extends Button {

	/**
	 * Renders field
	 *
	 * @return string
	 */
	public function renderField() {
		return sprintf(
			'<button type="submit" name="%1$s" value="%2$s" id="%3$s" %4$s>%1$s</button>',
			$this->name(),
			$this->value(),
			$this->identify(),
			$this->attributes()->toString()
		);
	}
}
