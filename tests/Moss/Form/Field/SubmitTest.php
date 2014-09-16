<?php
namespace Moss\Form\Field;

class SubmitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new Submit('name', 'value', array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    public function testIdentifyFromName()
    {
        $field = new Submit('name', 'value', array());
        $this->assertEquals('name', $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new Submit('name', 'value');
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
        $field = new Submit('name', 'value');
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new Submit('name', 'value', array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new Submit('name', 'value', array());
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
        $field = new Submit($actual, 'value', array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new Submit(null, 'value', array());
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
        $field = new Submit('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new Submit('name', null, array());
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
        $field = new Submit('name', 'value', array());
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $field->errors());
    }

    public function testRequired()
    {
        $field = new Submit('name', 'value', array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new Submit('name', 'value', array());
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new Submit('name', 'value');
        $this->assertNull($field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new Submit('name', 'value', array('label' => 'Press this'));
        $this->assertEquals('<button type="submit" id="name" name="name" value="value">Press this</button>', $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new Submit('name', 'value', array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new Submit('name', 'value', array());
        $field->condition(false, 'Error');

        $this->assertEquals('', $field->renderError());
    }

    public function testRender()
    {
        $field = new Submit('name', 'value', array('id' => 'id', 'label' => 'Submit text', 'class' => array('foo')));
        $this->assertEquals('<button type="submit" id="id" name="name" value="value" class="foo">Submit text</button>', $field->render());
    }

    public function testToString()
    {
        $field = new Submit('name', 'value', array('id' => 'id', 'label' => 'Submit text', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}