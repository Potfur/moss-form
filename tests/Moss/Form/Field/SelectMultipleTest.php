<?php
namespace Moss\Form\Field;

use Moss\Form\Option;

class SelectMultipleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new SelectMultiple('name', array('value'), array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    public function testIdentifyFromName()
    {
        $field = new SelectMultiple('name', 'value', array());
        $this->assertEquals('name', $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new SelectMultiple('name', array('value'), array());
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
        $field = new SelectMultiple('name', array('value'));
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new SelectMultiple('name', array('value'), array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new SelectMultiple('name', array('value'), array());
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
        $field = new SelectMultiple($actual, array('value'), array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new SelectMultiple(null, array('value'), array());
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
        $field = new SelectMultiple('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new SelectMultiple('name', null, array());
        $this->assertEquals($expected, $field->value($actual));
    }

    public function valueProvider()
    {
        return array(
            array(array('foo'), array('foo')),
            array(array('Bar'), array('Bar')),
            array(array('yada yada'), array('yada yada')),
            array(array('do[ku]'), array('do[ku]'))
        );
    }

    public function testError()
    {
        $field = new SelectMultiple('name', array('value'), array());
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $field = new SelectMultiple('name', array('value'), array('required' => true));
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
        $field = new SelectMultiple('name', array('value'), array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new SelectMultiple('name', array('value'), array());
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new SelectMultiple('name', array('value'), array('required'));
        $this->assertEquals('<label for="name">name<sup>*</sup></label>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new SelectMultiple('name', array('value'), array('required'));

        $expected = array(
            '<select id="name" name="name" required="required">',
            '<option value="" id="name_empty">---</option>',
            '</select>'
        );

        $this->assertEquals(implode(PHP_EOL, $expected), $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new SelectMultiple('name', array('value'), array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new SelectMultiple('name', array('value'), array());
        $field->condition(false, 'Error')
            ->validate();

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRenderNoOption()
    {
        $expected = array(
            '<label for="id">label</label><select id="id" name="name" class="foo">',
            '<option value="" id="id_empty">---</option>',
            '</select>'
        );

        $attributes = array(
            'id' => 'id',
            'label' => 'label',
            'class' => array('foo')
        );

        $options = array();

        $field = new SelectMultiple('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderMultipleOptions()
    {
        $expected = array(
            '<label for="id">label<sup>*</sup></label><select id="id" name="name" required="required" class="foo">',
            '<option id="id_0" value="1"/>Some label 1</option>',
            '<option id="id_1" value="2"/>Some label 2</option>',
            '</select>'
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

        $field = new SelectMultiple('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderSubOptions()
    {
        $expected = array(
            '<label for="id">label<sup>*</sup></label><select id="id" name="name" required="required" class="foo">',
            '<option id="id_0" value="1"/>Some label 1</option><optgroup label="Some label 1"><option id="id_0" value="1.2"/>Some label 1.2</option></optgroup>',
            '<option id="id_1" value="2"/>Some label 2</option>',
            '</select>',
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

        $field = new SelectMultiple('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testToString()
    {
        $field = new SelectMultiple('name', array('value'), array('id' => 'id', 'label' => 'label', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}