<?php
namespace Moss\Form;

use Moss\Form\FormInterface;
use Moss\Form\Fieldset;


/**
 * Object oriented form representation
 * Form is represented as unordered list
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Form extends Fieldset implements FormInterface
{
    /**
     * Constructor
     *
     * @param string $action     target url
     * @param string $method     sending method
     * @param array  $attributes additional attributes
     * @param string $enctype    encoding type
     */
    public function __construct($action, $method = 'post', $attributes = array(), $enctype = 'multipart/form-data')
    {
        parent::__construct(null, array(), $attributes);

        $this->action($action);
        $this->method($method);
        $this->enctype($enctype);
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
            $this->attributes->set('action', $action);
        }

        return $this->attributes->get('action');
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
            $this->attributes->set('method', $method);
        }

        return $this->attributes->get('method');
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
            $this->attributes->set('enctype', $enctype);
        }

        return $this->attributes->get('enctype');
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        $nodes = array();

        $nodes[] = sprintf('<form %s>', $this->attributes->render());
        $nodes[] = '<fieldset>';

        $this->renderLabel($nodes);
        $this->renderHiddenFields($nodes);
        $this->renderVisibleFields($nodes, array('id' => null, 'action' => null, 'method' => null, 'enctype' => null));

        $nodes[] = '</fieldset>';
        $nodes[] = '</form>';

        return implode("\n", $nodes);
    }
}
