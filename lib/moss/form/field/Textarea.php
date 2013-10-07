<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use \moss\form\Field;

/**
 * Textarea
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Textarea extends Field
{

    protected $cols = 20;
    protected $rows = 10;

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param null   $label      field label
     * @param bool   $required   if true "required" tag will be inserted into field label
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, $label = null, $required = false, $attributes = array())
    {
        $this->name($name);
        $this->value($value);
        $this->label($label, $required);
        $this->attributes = new AttributesBag($attributes);
        $this->errors = new ErrorsBag();
    }

    /**
     * Checks if field is visible
     * By default all fields are visible
     *
     * @return bool
     */
    public function isVisible()
    {
        return true;
    }


    /**
     * Sets field width in columns
     *
     * @param int $cols number of columns
     *
     * @return int
     */
    public function cols($cols = null)
    {
        if ($cols !== null) {
            $this->cols = (int) $cols;
        }

        return $this->cols;
    }

    /**
     * Sets field height in rows
     *
     * @param int $rows number of rows
     *
     * @return int
     */
    public function rows($rows = null)
    {
        if ($rows !== null) {
            $this->rows = (int) $rows;
        }

        return $this->rows;
    }

    /**
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        return sprintf(
            '<textarea name="%s" id="%s" rows="%u" cols="%u" %s>%s</textarea>',
            $this->name(),
            $this->identify(),
            $this->rows(),
            $this->cols(),
            $this
                ->attributes()
                ->toString(array('required' => $this->required() ? 'required' : null)),
            $this->value()
        );
    }
}
