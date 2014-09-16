<?php

/*
 * This file is part of the Moss form package
 *
 * (c) Michal Wachowski <wachowski.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moss\Form\Bag;

/**
 * Options container
 *
 * @package Moss Form
 * @author  Michal Wachowski <wachowski.michal@gmail.com>
 */
class OptionBag extends AbstractBag implements BagInterface
{
    /**
     * Returns error messages as string
     *
     * @param array $elements
     *
     * @return string
     */
    public function render(array $elements = array())
    {
        $storage = array_merge($elements, $this->storage);
        if (empty($storage)) {
            return null;
        }

        $result = array();
        foreach ($storage as $msg) {
            $result[] = sprintf('<li>%s</li>', $msg);
        }

        return sprintf('<ul class="error">%s</ul>', implode('', $result));
    }

    /**
     * Returns error messages as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}