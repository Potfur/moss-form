<?php
namespace moss\form;


interface BagInterface
{

    /**
     * Counts all elements in bag
     *
     * @return int
     */
    public function count();

    /**
     * Retrieves elements attribute value
     *
     * @param string $name attribute name
     *
     * @return mixed
     */
    public function get($name = null);

    /**
     * Sets elements attribute
     * If attribute exists - overwrites it
     *
     * @param string $name  attribute name
     *
     * @return $this
     */
    public function set($name);

    /**
     * Remove value or values from elements attribute
     * If no value passed, removes entire attribute
     *
     * @param string $name  attribute to remove from
     *
     * @return $this
     */
    public function remove($name = null);

    /**
     * Returns all options
     *
     * @return array
     */
    public function all();

    /**
     * Removes all options
     *
     * @return $this
     */
    public function reset();

    /**
     * Returns all not empty attributes as string
     *
     * @param array $additional
     *
     * @return string
     */
    public function toString($additional = array());
}