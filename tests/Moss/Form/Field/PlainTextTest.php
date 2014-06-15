<?php
namespace Moss\Form\Field;

class PlainTextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new PlainText('Some text', array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new PlainText('Some text');
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
        $field = new PlainText('Some text');
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new PlainText('Some text', array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new PlainText('Some text', array());
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
     * @dataProvider valueProvider
     */
    public function testValueFromConstructor($actual, $expected)
    {
        $field = new PlainText($actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new PlainText('Some text', array());
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
        $field = new PlainText('Some text', array());
        $this->assertInstanceOf('\Moss\Form\ErrorBag', $field->errors());
    }

    public function testRequired()
    {
        $field = new PlainText('Some text', array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new PlainText('Some text', array());
        $this->assertInstanceOf('\Moss\Form\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new PlainText('Some text');
        $this->assertNull($field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new PlainText('Some text');
        $this->assertEquals('<p >Some text</p>', $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new PlainText('Some text', array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new PlainText('Some text', array());
        $field->condition(false, 'Error');

        $this->assertEquals('', $field->renderError());
    }

    public function testRender()
    {
        $field = new PlainText('Some text', array('id' => 'id', 'class' => array('foo')));
        $this->assertEquals('<p id="id" class="foo">Some text</p>', $field->render());
    }

    public function testToString()
    {
        $field = new PlainText('Some text', array('id' => 'id', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}