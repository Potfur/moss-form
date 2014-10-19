<?php
namespace Moss\Form\Field;

use Moss\Form\Option;

class RadioTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new Radio('name', 'value', array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    public function testIdentifyFromName()
    {
        $field = new Radio('name', 'value', array());
        $this->assertEquals('name', $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new Radio('name', 'value', array());
        $this->assertEquals($expected, $field->identify($actual));
    }

    public function identifyProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do_ku')
        );
    }

    public function testIsVisible()
    {
        $field = new Radio('name', 'value');
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new Radio('name', 'value', array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new Radio('name', 'value', array());
        $this->assertEquals($expected, $field->label($actual));
    }

    public function labelProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'Bar'),
            array('yada yada', 'yada yada'),
            array('do[ku]', 'do[ku]')
        );
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromConstructor($actual, $expected)
    {
        $field = new Radio($actual, 'value', array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new Radio(null, 'value', array());
        $this->assertEquals($expected, $field->name($actual));
    }

    public function nameProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'Bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do[ku]')
        );
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromConstructor($actual, $expected)
    {
        $field = new Radio('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new Radio('name', null, array());
        $this->assertEquals($expected, $field->value($actual));
    }

    public function valueProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'Bar'),
            array('yada yada', 'yada yada'),
            array('do[ku]', 'do[ku]')
        );
    }

    public function testError()
    {
        $field = new Radio('name', 'value', array());
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $field = new Radio('name', 'value', array('required' => true));
        $field->condition($condition, 'Error');
        $this->assertEquals($isValid, $field->isValid());
    }

    public function conditionProvider()
    {
        return array(
            array('/^[a-z]+$/', true),
            array('/^[0-9]+$/', false),
            array(array('value'), true),
            array(array(), false),
            array(
                function ($value) {
                    return $value === 'value';
                },
                true
            ),
            array(
                function ($value) {
                    return $value !== 'value';
                },
                false,
            ),
            array(true, true),
            array(false, false)
        );
    }

    public function testRequired()
    {
        $field = new Radio('name', 'value', array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new Radio('name', 'value', array());
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new Radio('name', 'value', array('required'));
        $this->assertEquals('<span>name<sup>*</sup></span>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new Radio('name', 'value', array('required'));

        $expected = array(
            '<ul id="name">',
            '<li class="options"><input type="radio" id="name_empty" name="name[]" value="value" required="required"/><label for="name_empty" class="inline">--</label></li>',
            '</ul>'
        );

        $this->assertEquals(implode(PHP_EOL, $expected), $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new Radio('name', 'value', array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new Radio('name', 'value', array());
        $field->condition(false, 'Error')
            ->validate();

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRenderNoOption()
    {
        $expected = array(
            '<span>label</span><ul id="id" class="foo">',
            '<li class="options"><input type="radio" id="id_empty" name="name[]" value="value" class="foo"/><label for="id_empty" class="inline">--</label></li>',
            '</ul>'
        );

        $attributes = array(
            'id' => 'id',
            'label' => 'label',
            'class' => array('foo')
        );

        $options = array();

        $field = new Radio('name', 'value', $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderMultipleOptions()
    {
        $expected = array(
            '<span>label<sup>*</sup></span><ul id="id" class="foo">',
            '<li class="options"><input type="radio" id="id_0" name="name" value="1" required="required"/><label for="id_0" class="inline">Some label 1</label></li>',
            '<li class="options"><input type="radio" id="id_1" name="name" value="2" required="required"/><label for="id_1" class="inline">Some label 2</label></li>',
            '</ul>'
        );

        $attributes = array(
            'id' => 'id',
            'label' => 'label',
            'required',
            'class' => array('foo')
        );

        $options = array(
            new Option('Some label 1', 1),
            new Option('Some label 2', 2)
        );

        $field = new Radio('name', 'value', $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderSubOptions()
    {
        $expected = array(
            '<span>label<sup>*</sup></span><ul id="id" class="foo">',
            '<li class="options"><input type="radio" id="id_0" name="name" value="1" required="required"/><label for="id_0" class="inline">Some label 1</label><ul class="options">',
            '<li class="options"><input type="radio" id="id_1" name="name" value="1.2" required="required"/><label for="id_1" class="inline">Some label 1.2</label></li></ul></li>',
            '<li class="options"><input type="radio" id="id_2" name="name" value="2" required="required"/><label for="id_2" class="inline">Some label 2</label></li>',
            '</ul>',
        );

        $attributes = array(
            'id' => 'id',
            'label' => 'label',
            'required',
            'class' => array('foo')
        );

        $options = array(
            new Option('Some label 1', 1, array(), array(new Option('Some label 1.2', 1.2))),
            new Option('Some label 2', 2)
        );

        $field = new Radio('name', 'value', $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testToString()
    {
        $field = new Radio('name', 'value', array('id' => 'id', 'label' => 'label', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}