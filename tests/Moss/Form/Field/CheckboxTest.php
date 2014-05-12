<?php
namespace Moss\Form\Field;

use Moss\Form\Option;

class CheckboxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new Checkbox('name', array('value'), array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new Checkbox('name', array('value'), array());
        $this->assertEquals($expected, $field->identify($actual));
    }

    public function identifyProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do_ku'),
            array(null, 'name')
        );
    }

    public function testIsVisible()
    {
        $field = new Checkbox('name', array('value'));
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new Checkbox('name', array('value'), array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new Checkbox('name', array('value'), array());
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
        $field = new Checkbox($actual, array('value'), array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new Checkbox(null, array('value'), array());
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
        $field = new Checkbox('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new Checkbox('name', null, array());
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
        $field = new Checkbox('name', array('value'), array());
        $this->assertInstanceOf('\Moss\Form\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $field = new Checkbox('name', array('value'), array('required' => true));
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
        $field = new Checkbox('name', array('value'), array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new Checkbox('name', array('value'), array());
        $this->assertInstanceOf('\Moss\Form\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new Checkbox('name', array('value'), array('required'));
        $this->assertEquals('<span>name<sup>*</sup></span>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new Checkbox('name', array('value'), array('required'));

        $expected = array(
            '<ul id="name">',
            '<li><input type="checkbox" name="name[]" value="" id="name_empty"/><label for="name_empty" class="inline">---</label></li>',
            '</ul>'
        );

        $this->assertEquals(implode(PHP_EOL, $expected), $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new Checkbox('name', array('value'), array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new Checkbox('name', array('value'), array());
        $field->condition(false, 'Error');

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRenderNoOption()
    {
        $expected = array(
            '<span>label</span><ul id="id" class="foo">',
            '<li><input type="checkbox" name="name[]" value="" id="id_empty"/><label for="id_empty" class="inline">---</label></li>',
            '</ul>'
        );

        $attributes = array(
            'id' => 'id',
            'label' => 'label',
            'class' => array('foo')
        );

        $options = array();

        $field = new Checkbox('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderOneOption()
    {
        $expected = array(
            '<ul id="id" class="foo">',
            '<li class="options"><input type="checkbox" id="id_0" name="name[]" value="1" required="required"/><label for="id_0" class="inline">Some label 1</label></li>',
            '</ul>'
        );

        $attributes = array(
            'id' => 'id',
            'label' => 'label',
            'required',
            'class' => array('foo')
        );

        $options = array(
            new Option('Some label 1', 1)
        );

        $field = new Checkbox('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderMultipleOptions()
    {
        $expected = array(
            '<span>label<sup>*</sup></span><ul id="id" class="foo">',
            '<li class="options"><input type="checkbox" id="id_0" name="name[]" value="1" required="required"/><label for="id_0" class="inline">Some label 1</label></li>',
            '<li class="options"><input type="checkbox" id="id_1" name="name[]" value="2" required="required"/><label for="id_1" class="inline">Some label 2</label></li>',
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

        $field = new Checkbox('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testRenderSubOptions()
    {
        $expected = array(
            '<span>label<sup>*</sup></span><ul id="id" class="foo">',
            '<li class="options"><input type="checkbox" id="id_0" name="name[]" value="1" required="required"/><label for="id_0" class="inline">Some label 1</label><ul class="options">',
            '<li class="options"><input type="checkbox" id="id_1" name="name[]" value="1.2" required="required"/><label for="id_1" class="inline">Some label 1.2</label></li></ul></li>',
            '<li class="options"><input type="checkbox" id="id_2" name="name[]" value="2" required="required"/><label for="id_2" class="inline">Some label 2</label></li>',
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

        $field = new Checkbox('name', array('value'), $attributes, $options);
        $this->assertEquals(implode(PHP_EOL, $expected), $field->render());
    }

    public function testToString()
    {
        $field = new Checkbox('name', array('value'), array('id' => 'id', 'label' => 'label', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}