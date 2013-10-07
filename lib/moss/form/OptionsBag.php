<?php
namespace moss\form;

/**
 * Abstract form element options container
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class OptionsBag implements BagInterface {

	private $options = array();

	/**
	 * Constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = array()) {
		$this->set($options);
	}

	/**
	 * Counts all elements in bag
	 *
	 * @return int
	 */
	public function count() {
		return count($this->options);
	}

	/**
	 * Retrieves option
	 *
	 * @param string $identifier option identifier
	 *
	 * @return mixed
	 * @throws OptionException
	 */
	public function get($identifier = null) {
		if($identifier === null) {
			return $this->all();
		}

		if(!is_scalar($identifier)) {
			throw new OptionException(sprintf('Invalid option identifier in %s, got "%s"', get_class($this), is_object($identifier) ? get_class($identifier) : gettype($identifier), get_class($this)));
		}

		if(!isset($this->options[$identifier])) {
			return null;
		}

		return $this->options[$identifier];
	}

	/**
	 * Sets options
	 *
	 * @param array|OptionInterface $option
	 *
	 * @return $this
	 * @throws OptionException
	 */
	public function set($option) {
		if(is_array($option)) {
			$this->reset();

			foreach($option as $o) {
				$this->add($o);
				continue;
			}

			return $this;
		}

		if(!$option instanceof OptionInterface) {
			throw new OptionException(sprintf('Must be array of OptionInterfaces or OptionInterface, got "%s"', is_object($option) ? get_class($option) : gettype($option)));
		}

		return $this->add($option);
	}

	/**
	 * Adds option
	 *
	 * @param OptionInterface $option
	 *
	 * @return $this
	 */
	public function add(OptionInterface $option) {
		$this->options[$option->identify()] = $option;

		return $this;
	}

	/**
	 * Remove option
	 * If no value passed, removes all options
	 *
	 * @param string $identifier
	 *
	 * @return $this
	 * @throws OptionException
	 */
	public function remove($identifier = null) {
		if($identifier === null) {
			return $this->reset();
		}

		if(!is_scalar($identifier)) {
			throw new OptionException(sprintf('Invalid option identifier in %s, got "%s"', get_class($this), is_object($identifier) ? get_class($identifier) : gettype($identifier), get_class($this)));
		}

		if(isset($this->options[$identifier])) {
			unset($this->options[$identifier]);
		}

		return $this;
	}

	/**
	 * Returns all options
	 *
	 * @return array
	 */
	public function all() {
		return $this->options;
	}

	/**
	 * Removes all options
	 *
	 * @return $this
	 */
	public function reset() {
		$this->options = array();

		return $this;
	}

	/**
	 * Returns all not empty attributes as string
	 *
	 * @param array $additional
	 *
	 * @return string
	 */
	public function toString($additional = array()) {
		// TODO: Implement toString() method.
	}


}