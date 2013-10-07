<?php
namespace moss\form\field;

use moss\form\field\Checkbox;
use moss\form\OptionGroupInterface;
use moss\form\OptionInterface;

/**
 * Select form field
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class SelectMultiple extends Checkbox {

	protected $options = array();

	/**
	 * Renders label
	 *
	 * @return string
	 */
	public function renderLabel() {
		if(!$this->label()) {
			return null;
		}

		return sprintf(
			'<label for="%s">%s%s</label>',
			$this->identify(),
			$this->label(),
			$this->required() ? '<sup>*</sup>' : null
		);
	}

	/**
	 * Renders field
	 *
	 * @return string
	 */
	public function renderField() {
		$nodes = array();

		$nodes[] = sprintf(
			'<select name="%s" id="%s" multiple="multiple" %s>',
			$this->name(),
			$this->identify(),
			$this->attributes()->toString(array('required' => $this->required() ? 'required' : null))
		);

		$options = $this->options()->all();
		if(empty($options)) {
			$nodes[] = sprintf('<option value="" id="%s">---</option>', $this->identify() . '_empty');
		}
		else {
			foreach($options as $Option) {
				$nodes[] = $this->renderOption($Option);
			}
		}

		$nodes[] = sprintf('</select>');

		return implode("\n", $nodes);
	}

	/**
	 * Renders single checkbox button
	 *
	 * @param OptionInterface $Option
	 *
	 * @return string
	 */
	protected function renderOption(OptionInterface $Option) {
		$id = $this->identify() . '_' . $Option->identify();

		$options = $Option->options()->all();
		if(!empty($options)) {
			return $this->renderSubOptions($Option);
		}

		return sprintf(
			'<option value="%s" id="%s" %s>%s</option>%s',
			$Option->value(),
			$id,
			$Option
				->attributes()
				->toString(array('selected' => $Option->value() == $this->value ? 'selected' : null)),
			$Option->label(),
			$this->renderSubOptions($Option)
		);
	}

	/**
	 * Renders subn options
	 *
	 * @param OptionInterface $Option
	 *
	 * @return null|string
	 */
	protected function renderSubOptions(OptionInterface $Option) {
		$subOptions = $Option
			->options()
			->all();

		if(empty($subOptions)) {
			return null;
		}

		$nodes = array();
		foreach($subOptions as $subOption) {
			$nodes[] = $this->renderOption($subOption);
		}

		return sprintf('<%1$s label="%2$s">%3$s</%1$s>', 'optgroup', $Option->label(), "\n" . implode("\n", $nodes));
	}

	/**
	 * Renders element
	 *
	 * @return string
	 */
	public function render() {
		return $this->renderLabel() . $this->renderField() . $this->renderError();
	}
}
