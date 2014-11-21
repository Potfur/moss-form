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

use Moss\Form\Bag\AbstractBag;
use Moss\Form\Bag\AttributeBag;
use Moss\Form\Bag\ErrorBag;

/**
 * Object oriented fieldset representation
 * Fieldset is represented as unordered lists
 * If fieldset is nested in existing form - list will be also nested
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class Fieldset extends AbstractBag implements FieldsetInterface
{

    /** @var \Moss\Form\Bag\AttributeBag */
    protected $attributes;

    protected $tag = array(
        'group' => 'ul',
        'element' => 'li'
    );

    /** @var ElementInterface[] */
    protected $storage = array();

    /**
     * Constructor
     *
     * @param null  $label      fieldset label, if set - will be used as form field key
     * @param array $fields     array containing fields
     * @param array $attributes additional attributes as associative array
     */
    public function __construct($label = null, $fields = array(), $attributes = array())
    {
        $this->attributes = new AttributeBag($attributes);

        $this->label($label);

        foreach ($fields as $key => $field) {
            $this->set($key, $field);
        }
    }

    /**
     * Returns field identifier
     * If no identifier is set - new is generated, based on field name
     *
     * @param null|string $identifier field identifier
     *
     * @return string
     */
    public function identify($identifier = null)
    {
        if ($identifier !== null) {
            $this->attributes->set('id', $identifier);
        } elseif (!$this->attributes->has('id')) {
            $this->attributes->set('id', $this->attributes->get('name'));
        }

        return $this->attributes->get('id');
    }

    /**
     * Sets field label
     *
     * @param string $label field label
     *
     * @return string
     */
    public function label($label = null)
    {
        if ($label !== null) {
            $this->attributes->set('label', $label);
        }

        return $this->attributes->get('label');
    }

    /**
     * Returns attribute bag interface
     *
     * @return \Moss\Form\Bag\AttributeBag
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Returns all error messages
     *
     * @return \Moss\Form\Bag\ErrorBag
     */
    public function errors()
    {
        $errors = new ErrorBag();
        foreach ($this->storage as $element) {
            if ($element instanceof FieldsetInterface || $element instanceof FieldInterface) {
                continue;
            }

            if (!$element->errors()
                ->has()
            ) {
                continue;
            }

            foreach ($element->errors()
                ->all() as $error) {
                $errors->add(null, $error);
            }
        }

        return $errors;
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
     * Checks if field is valid (if all conditions have been met)
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->storage as $element) {
            if (!$element->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns rendered and escaped fieldset
     *
     * @return string
     */
    public function prototype()
    {
        $str = $this->render();
        $str = htmlspecialchars($str);
        $str = str_replace(array("\r", "\n"), null, $str);

        return $str;
    }

    /**
     * Sets group tag
     *
     * @param string $tag
     *
     * @return $this
     */
    public function groupTag($tag)
    {
        $this->tag['group'] = (string) $tag;

        return $this;
    }

    /**
     * Sets tag for elements
     *
     * @param string $tag
     *
     * @return $this
     */
    public function elementTag($tag)
    {
        $this->tag['element'] = (string) $tag;

        return $this;
    }

    /**
     * Renders element
     *
     * @return string
     */
    public function render()
    {
        $nodes = array();

        $this->renderLabel($nodes);
        $this->renderHiddenFields($nodes);
        $this->renderVisibleFields($nodes);

        return implode('', $nodes);
    }

    /**
     * Renders label if set
     *
     * @param array $nodes
     */
    protected function renderLabel(&$nodes)
    {
        if ($this->attributes->get('label')) {
            $nodes[] = sprintf('<legend>%s</legend>', $this->attributes->get('label'));
        }
    }

    /**
     * Renders hidden fields
     *
     * @param array $nodes
     */
    protected function renderHiddenFields(&$nodes)
    {
        foreach ($this->storage as $field) {
            if ($field->isVisible() !== true) {
                $nodes[] = $field->render();
            }
        }
    }

    /**
     * Renders visible fields with structure
     *
     * @param array $nodes
     * @param array $attributes
     */
    protected function renderVisibleFields(&$nodes, array $attributes = array())
    {
        $nodes[] = sprintf(
            '<%s %s>',
            $this->tag['group'],
            $this->attributes->render(array_merge($attributes, array('id' => null, 'label' => null)))
        );

        foreach ($this->storage as $field) {
            if ($field->isVisible() === true) {
                $nodes[] = sprintf('<%1$s>%2$s</%1s>', $this->tag['element'], $field->render());
            }
        }

        $nodes[] = sprintf('</%s>', $this->tag['group']);
    }

    /**
     * Returns rendered fieldset
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
