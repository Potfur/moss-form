<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form\Field;

/**
 * Select date field set
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class SelectDate extends Date
{

    private $options = array(
        'year' => array('from' => null, 'to' => null, 'step' => 1),
        'month' => array('from' => 1, 'to' => 12, 'step' => 1),
        'day' => array('from' => 1, 'to' => 31, 'step' => 1),
        'hour' => array('from' => 0, 'to' => 23, 'step' => 1),
        'minute' => array('from' => 0, 'to' => 59, 'step' => 1),
        'second' => array('from' => 0, 'to' => 59, 'step' => 1),
    );

    /**
     * Constructor
     *
     * @param string $name       field name
     * @param null   $value      field value
     * @param array  $attributes additional attributes as associative array
     * @param int    $period     number of years in past and in future
     */
    public function __construct($name, $value = null, array $attributes = array(), $period = 25)
    {
        parent::__construct($name, $value, $attributes);

        $from = new \DateTime(sprintf('-%u years', $period));
        $to = new \DateTime(sprintf('+%u years', $period));

        $this->setYearsRange($from->format('Y'), $to->format('Y'))
            ->setMonthsRange()
            ->setDaysRange()
            ->setHoursRange()
            ->setMinutesRange()
            ->setSecondsRange();
    }

    /**
     * Sets year range
     *
     * @param int $form
     * @param int $to
     * @param int $step
     *
     * @return $this
     */
    public function setYearsRange($form = null, $to = null, $step = 1)
    {
        $this->range('year', $form, $to, $step);

        return $this;
    }

    /**
     * Sets month range
     *
     * @param int $form
     * @param int $to
     * @param int $step
     *
     * @return $this
     */
    public function setMonthsRange($form = 1, $to = 12, $step = 1)
    {
        $this->range('month', $form, $to, $step);

        return $this;
    }

    /**
     * Sets day range
     *
     * @param int $form
     * @param int $to
     * @param int $step
     *
     * @return $this
     */
    public function setDaysRange($form = 1, $to = 31, $step = 1)
    {
        $this->range('day', $form, $to, $step);

        return $this;
    }

    /**
     * Sets hours range
     *
     * @param int $form
     * @param int $to
     * @param int $step
     *
     * @return $this
     */
    public function setHoursRange($form = 0, $to = 23, $step = 1)
    {
        $this->range('hour', $form, $to, $step);

        return $this;
    }

    /**
     * Sets minutes range
     *
     * @param int $form
     * @param int $to
     * @param int $step
     *
     * @return $this
     */
    public function setMinutesRange($form = 0, $to = 59, $step = 1)
    {
        $this->range('minute', $form, $to, $step);

        return $this;
    }

    /**
     * Sets seconds range
     *
     * @param int $form
     * @param int $to
     * @param int $step
     *
     * @return $this
     */
    public function setSecondsRange($form = 0, $to = 59, $step = 1)
    {
        $this->range('second', $form, $to, $step);

        return $this->options['second'];
    }

    /**
     * @param string $key
     * @param int    $from
     * @param int    $to
     * @param int    $step
     */
    protected function range($key, $from, $to, $step)
    {
        $this->options[$key] = array('from' => (int) $from, 'to' => (int) $to, 'step' => (int) $step);
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

        $value = $this->value ? $this->value : new \DateTime();

        if ($key = $this->check('LoYy')) {
            $fields['{' . $key . '}'] = $this->renderSelect('year', $this->renderOptions($this->options['year'], $value->format('Y')));
        }

        if ($key = $this->check('FMmnt')) {
            $fields['{' . $key . '}'] = $this->renderSelect('month', $this->renderOptions($this->options['month'], $value->format('m')));
        }

        if ($key = $this->check('dDjlNSwz')) {
            $fields['{' . $key . '}'] = $this->renderSelect('day', $this->renderOptions($this->options['day'], $value->format('d')));
        }

        if ($key = $this->check('gGhH')) {
            $fields['{' . $key . '}'] = $this->renderSelect('hour', $this->renderOptions($this->options['hour'], $value->format('H')));
        }

        if ($key = $this->check('i')) {
            $fields['{' . $key . '}'] = $this->renderSelect('minute', $this->renderOptions($this->options['minute'], $value->format('i')));
        }

        if ($key = $this->check('s')) {
            $fields['{' . $key . '}'] = $this->renderSelect('second', $this->renderOptions($this->options['second'], $value->format('s')));
        }

        $format = $this->format;
        $format = preg_replace('/([a-z])/im', '<li>{$1}</li>', $format);
        $format = preg_replace('/([^a-z{}<>\/ ])/im', '<li>$1</li>', $format);
        $format = sprintf(
            '<ul id="%s" class="%s">%s</ul>',
            $this->attributes->get('id'),
            implode(' ', (array) $this->attributes->get('class')),
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
            $this->attributes->get('name'),
            $name,
            $options
        );
    }

    private function renderOptions($options, $selected = null)
    {
        $nodes = array();
        for ($i = $options['from']; $i <= $options['to']; $i += $options['step']) {
            $nodes[] = sprintf(
                '<option value="%1$s" %3$s>%2$s</option>',
                $i,
                str_pad($i, 2, '0', \STR_PAD_LEFT),
                $selected == $i ? 'selected="selected"' : null
            );
        }

        return implode('', $nodes);
    }
}

