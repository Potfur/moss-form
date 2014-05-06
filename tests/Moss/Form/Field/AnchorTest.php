<?php
namespace Moss\Form\Field;

class AnchorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new Anchor('Google it', 'http://google.com', array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new Anchor('Google it', 'http://google.com');
        $this->assertEquals($expected, $field->identify($actual));
    }

    public function identifyProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do_ku'),
            array(null, 'google_it')
        );
    }

    public function testIsVisible()
    {
        $field = new Anchor('Google it', 'http://google.com');
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new Anchor('Google it', 'http://google.com', array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new Anchor('Google it', 'http://google.com', array());
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
        $field = new Anchor($actual, 'http://google.com', array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new Anchor(null, 'http://google.com', array());
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
        $field = new Anchor('Google it', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new Anchor('Google it', null, array());
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
        $field = new Anchor('Google it', 'http://google.com', array());
        $this->assertInstanceOf('\Moss\Form\ErrorBag', $field->errors());
    }

    public function testRequired()
    {
        $field = new Anchor('Google it', 'http://google.com', array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new Anchor('Google it', 'http://google.com', array());
        $this->assertInstanceOf('\Moss\Form\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new Anchor('Google it', 'http://google.com');
        $this->assertNull($field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new Anchor('Google it', 'http://google.com');
        $this->assertEquals('<a id="google_it" href="http://google.com">Google it</a>', $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new Anchor('Google it', 'http://google.com', array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new Anchor('Google it', 'http://google.com', array());
        $field->condition(false, 'Error');

        $this->assertEquals('', $field->renderError());
    }

    public function testRender()
    {
        $field = new Anchor('Google it', 'http://google.com', array('id' => 'id', 'label' => 'Anchor text', 'class' => array('foo')));
        $this->assertEquals('<a id="id" href="http://google.com" class="foo">Anchor text</a>', $field->render());
    }

    public function testToString()
    {
        $field = new Anchor('Google it', 'http://google.com', array('id' => 'id', 'label' => 'Anchor text', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}