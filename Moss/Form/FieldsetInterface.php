<?php
namespace Moss\Form;

use Moss\Form\ElementInterface;

/**
 * Fieldset interface
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
interface FieldsetInterface extends ElementInterface, \ArrayAccess, \Iterator
{

    /**
     * Sets field label
     *
     * @param string $label field label
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
     * Returns all element attributes as array
     *
     * @return array
     */
    public function all();

    /**
     * Adds element to fieldset
     *
     * @param string           $identifier
     * @param ElementInterface $Element
     *
     * @return FieldsetInterface
     */
    public function set($identifier, ElementInterface $Element);

    /**
     * Returns element from fieldset
     *
     * @param string $identifier
     *
     * @return mixed|ElementInterface|FieldInterface|FieldsetInterface
     */
    public function get($identifier);

    /**
     * Removes element from fieldset
     *
     * @param string $identifier
     *
     * @return FieldsetInterface
     */
    public function remove($identifier);

    /**
     * Returns rendered and escaped fieldset
     *
     * @return string
     */
    public function prototype();
}
