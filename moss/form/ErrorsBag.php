<?php
namespace moss\form;

/**
 * Abstract form element errors container
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class ErrorsBag implements BagInterface
{

    private $errors = array();

    /**
     * Constructor
     *
     * @param array $errors
     */
    public function __construct($errors = array())
    {
        foreach ($errors as $message) {
            $this->set($message);
        }
    }

    /**
     * Counts all elements in bag
     *
     * @return int
     */
    public function count()
    {
        return count($this->errors);
    }

    /**
     * Retrieves error message
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function get($offset = null)
    {
        if ($offset === null) {
            return $this->all();
        }

        if (!isset($this->errors[$offset])) {
            return null;
        }

        return $this->errors[$offset];
    }

    /**
     * Sets error message
     *
     * @param string $error
     *
     * @return $this
     */
    public function set($error)
    {
        $this->errors[] = (string) $error;

        return $this;
    }

    /**
     * Remove error at offset
     * If no value passed, removes all errors
     *
     * @param int $offset
     *
     * @return $this
     */
    public function remove($offset = null)
    {
        if ($offset === null) {
            return $this->reset();
        }

        if (isset($this->errors[$offset])) {
            unset($this->errors[$offset]);
        }

        return $this;
    }

    /**
     * Returns all options
     *
     * @return array
     */
    public function all()
    {
        return $this->errors;
    }

    /**
     * Removes all options
     *
     * @return $this
     */
    public function reset()
    {
        $this->errors = array();
        return $this;
    }


    /**
     * Returns error messages as string
     *
     * @param array $additional
     *
     * @return string
     */
    public function toString($additional = array())
    {
        $errors = array_merge((array) $this->errors, $additional);
        if (empty($errors)) {
            return null;
        }

        $result = array();
        foreach ($errors as $msg) {
            $result[] = sprintf('<li>%s</li>', $msg);
        }

        return sprintf('<ul class="error">%s</ul>', implode('', $result));
    }
}