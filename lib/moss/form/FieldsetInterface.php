<?php
namespace moss\form;

use moss\form\ElementInterface;

/**
 * Fieldset interface
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
interface FieldsetInterface extends ElementInterface, \ArrayAccess, \Iterator {

	/**
	 * Sets field label
	 *
	 * @param string $label    field label
	 *
	 * @return string
	 */
	public function label($label = null);

	/**
	 * Returns attribute bag interface
	 *
	 * @return AttributesBag
	 */
	public function attributes();

	/**
	 * Sets fieldset fields
	 * Fields must be passed as array key - value pairs, where key is field/fieldsets identifier
	 *
	 * @param array $fields array containing fields
	 *
	 * @return $this
	 */
	public function setFields(array $fields);

	/**
	 * Returns all element attributes as array
	 *
	 * @return array
	 */
	public function getFields();

	/**
	 * Adds element to fieldset
	 *
	 * @param string           $identifier
	 * @param ElementInterface $Element
	 *
	 * @return FieldsetInterface
	 */
	public function setField($identifier, ElementInterface $Element);

	/**
	 * Returns element from fieldset
	 *
	 * @param string $identifier
	 *
	 * @return ElementInterface
	 */
	public function getField($identifier);

	/**
	 * Removes element from fieldset
	 *
	 * @param string $identifier
	 *
	 * @return FieldsetInterface
	 */
	public function removeField($identifier);
}
