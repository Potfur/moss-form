<?php
namespace moss\form;

use moss\form\FormInterface;
use moss\form\Fieldset;


/**
 * Object oriented form representation
 * Form is represented as unordered list
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Form extends Fieldset implements FormInterface
{

    protected $action;
    protected $method;
    protected $enctype;

    /**
     * Constructor
     *
     * @param string $action     target url
     * @param string $method     sending method
     * @param string $enctype    encoding type
     * @param array  $attributes additional attributes
     */
    public function __construct($action, $method = 'post', $enctype = 'multipart/form-data', $attributes = array())
    {
        $this->action($action);
        $this->method($method);
        $this->enctype($enctype);

        parent::__construct(null, array(), $attributes);
    }

    /**
     * Sets forms action
     *
     * @param string $action
     *
     * @return Form
     */
    public function action($action = null)
    {
        if ($action !== null) {
            $this->action = $action;
        }

        return $this->action;
    }

    /**
     * Sets forms sending method
     *
     * @param string $method
     *
     * @return Form
     */
    public function method($method = null)
    {
        if ($method !== null) {
            $this->method = $method;
        }

        return $this->method;
    }

    /**
     * Sets forms encoding type
     *
     * @param string $enctype
     *
     * @return Form
     */
    public function enctype($enctype = null)
    {
        if ($enctype !== null) {
            $this->enctype = $enctype;
        }

        return $this->enctype;
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        $nodes = array();

        $nodes[] = sprintf(
            '<form action="%s" method="%s" %s>',
            $this->action(),
            $this->method(),
            $this
                ->attributes()
                ->toString(array('enctype' => $this->enctype()))
        );

        $nodes[] = '<fieldset>';

        foreach ($this->struct as $field) {
            if($field->isVisible() === true) {
                continue;
            }

            $nodes[] = (string) $field;
        }

        $nodes[] = sprintf('<%s %s>', $this->tag['group'], '');

        if($this->label) {
            $nodes[] = sprintf('<legend>%s</legend>', $this->label());
        }

        foreach ($this->struct as $field) {
            if($field->isVisible() === false) {
                continue;
            }

            $nodes[] = sprintf('<%1$s>%2$s</%1s>', $this->tag['element'], (string) $field);
        }

        $nodes[] = sprintf('</%s>', $this->tag['group']);

        $nodes[] = '</fieldset>';
        $nodes[] = '</form>';

        return implode("\n", $nodes);
    }
}
