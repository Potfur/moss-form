<?php
namespace moss\form\field;

use moss\form\AttributesBag;
use moss\form\ErrorsBag;
use moss\form\Field;

/**
 * Input/Text
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class SelectDate extends Field
{

    private $format = 'Y-m-d H:i:s';
    private $period = 50;

    /** @var \DateTime */
    protected $value;


    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param null   $label      field label
     * @param bool   $required   if true "required" tag will be inserted into label
     * @param array  $attributes additional attributes as associative array
     */
    public function __construct($name, $value = null, $label = null, $required = false, $attributes = array())
    {
        $this->name($name);
        $this->value($value);
        $this->label($label, $required);
        $this->required($required);
        $this->attributes = new AttributesBag($attributes);
        $this->errors = new ErrorsBag();
    }

    /**
     * Sets date format
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format = null)
    {
        if ($format !== null) {
            $this->format = $format;
        }

        return $this->format;
    }

    /**
     * Sets year period range
     *
     * @param int $period
     *
     * @return int
     */
    public function period($period = null)
    {
        if ($period !== null) {
            $this->period = (int) $period;
        }

        return $this->period;
    }

    /**
     * Sets field value
     *
     * @param mixed $value field value
     *
     * @return \DateTime
     */
    public function value($value = null)
    {
        if ($value !== null) {
            if (!$value instanceof \DateTime) {
                $value = new \DateTime($value);
            }

            $this->value = $value;
        }

        return $this->value;
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
     * Renders field
     *
     * @return string
     */
    public function renderField()
    {
        $fields = array();

        if ($key = $this->check('LoYy')) {
            $s = date('Y') - ceil($this->period / 2);
            $e = $s + (int) $this->period;
            $fields['{' . $key . '}'] = $this->renderSelect('year', $this->renderOptions($s, $e, $this->value->format('Y')));
        }

        if ($key = $this->check('FMmnt')) {
            $fields['{' . $key . '}'] = $this->renderSelect('month', $this->renderOptions(1, 12, $this->value->format('m')));
        }

        if ($key = $this->check('dDjlNSwz')) {
            $fields['{' . $key . '}'] = $this->renderSelect('day', $this->renderOptions(1, 31, $this->value->format('d')));
        }

        if ($key = $this->check('gGhH')) {
            $fields['{' . $key . '}'] = $this->renderSelect('hour', $this->renderOptions(0, 23, $this->value->format('H')));
        }

        if ($key = $this->check('i')) {
            $fields['{' . $key . '}'] = $this->renderSelect('minute', $this->renderOptions(0, 59, $this->value->format('i')));
        }

        if ($key = $this->check('s')) {
            $fields['{' . $key . '}'] = $this->renderSelect('year', $this->renderOptions(0, 59, $this->value->format('s')));
        }

        $format = $this->format;
        $format = preg_replace('/([a-z])/im', '<li>{$1}</li>', $format);
        $format = preg_replace('/([^a-z{}<>\/ ])/im', '<li>$1</li>', $format);
        $format = sprintf(
            '<ul %s>%s</ul>',
            $this->attributes()->toString(),
            $format
        );

        return strtr($format, $fields);
    }

    private function check($chars)
    {
        foreach (str_split($chars) as $letter) {
            if (strpos($this->format, $letter) !== false) {
                return $letter;
            }
        }

        return false;
    }

    private function renderSelect($name, $options)
    {
        return sprintf(
            '<select name="%1$s[%2$s]" class="date %2$s small">%3$s</select>',
            $this->name,
            $name,
            implode($options)
        );
    }

    private function renderOptions($start, $end, $selected = null)
    {
        $options = array();
        for ($i = $start; $i <= $end; $i++) {
            $v = str_pad($i, 2, '0', \STR_PAD_LEFT);
            $options[] = sprintf('<option value="%1$s" %3$s>%2$s</option>', $v, $i, $selected == $v ? 'selected="selected"' : null);
        }

        return $options;
    }
}

