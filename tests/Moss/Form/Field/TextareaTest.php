<?php
namespace Moss\Form\Field;

class TextareaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new Textarea('name', 'value', array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new Textarea('name', 'value', array());
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
        $field = new Textarea('name', 'value');
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new Textarea('name', 'value', array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new Textarea('name', 'value', array());
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
        $field = new Textarea($actual, 'value', array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new Textarea(null, 'value', array());
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
        $field = new Textarea('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new Textarea('name', null, array());
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
        $field = new Textarea('name', 'value', array());
        $this->assertInstanceOf('\Moss\Form\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $field = new Textarea('name', 'value', array('required' => true));
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
        $field = new Textarea('name', 'value', array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new Textarea('name', 'value', array());
        $this->assertInstanceOf('\Moss\Form\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new Textarea('name', 'value', array('required'));
        $this->assertEquals('<label for="name">name<sup>*</sup></label>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new Textarea('name', 'value', array('required'));
        $this->assertEquals('<textarea id="name" name="name" required="required" rows="10" cols="20">value</textarea>', $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new Textarea('name', 'value', array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new Textarea('name', 'value', array());
        $field->condition(false, 'Error');

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRender()
    {
        $field = new Textarea('name', 'value', array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals('<label for="id">label<sup>*</sup></label><textarea id="id" name="name" required="required" rows="10" cols="20" class="foo">value</textarea>', $field->render());
    }

    public function testToString()
    {
        $field = new Textarea('name', 'value', array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}