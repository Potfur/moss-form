<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form;

/**
 * Parameter bag interface
 *
 * @package  Moss Form
 * @author   Michal Wachowski <wachowski.michal@gmail.com>
 */
interface BagInterface extends \ArrayAccess, \Iterator, \Countable
{
    /**
     * Retrieves offset value
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function get($offset = null);

    /**
     * Sets value to offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($offset, $value);

    /**
     * Returns true if offset exists in bag
     *
     * @param string $offset
     *
     * @return bool
     */
    public function has($offset = null);

    /**
     * Adds value or values to offset
     * Creates offset if it does not exists
     *
     * @param string       $offset offset to add to
     * @param string|array $value  value or array of values added
     *
     * @return $this
     */
    public function add($offset, $value);

    /**
     * Removes offset from bag
     * If no offset set, removes all values
     *
     * @param string $offset offset to remove from
     *
     * @return $this
     */
    public function remove($offset = null);

    /**
     * Returns all options
     * If array passed, becomes bag content
     *
     * @param array $array overwrites values
     *
     * @return array
     */
    public function all($array = array());

    /**
     * Removes all options
     *
     * @return $this
     */
    public function reset();

    /**
     * Returns attributes as string
     *
     * @return string
     */
    function __toString();
}
