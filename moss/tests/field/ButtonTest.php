<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;

class ButtonTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Button('name', 'value', array('class' => 'foo'));
    }

    public function tearDown()
    {
    }

    /**
     * @dataProvider nameProvider
     */
    public function testName($actual, $expected)
    {
        $this->assertEquals($expected, $this->field->name($actual));
    }

    public function nameProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'Bar'),
            array('yada yada', 'yada yada'),
            array('do[ku]', 'do[ku]')
        );
    }

    public function testCondition()
    {
        $this->field->condition('foo', 'Error');
        $this->assertTrue($this->field->isValid());
    }

    public function testRenderLabel()
    {
        $this->assertNull($this->field->renderLabel());
    }

    public function testRenderField()
    {
        $this->assertEquals('<button type="button" name="name" value="value" id="name" class="foo">name</button>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertNull($this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<button type="button" name="name" value="value" id="name" class="foo">name</button>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<button type="button" name="name" value="value" id="name" class="foo">name</button>', $this->field->__toString());
    }
}
