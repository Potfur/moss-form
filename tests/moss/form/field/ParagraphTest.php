<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;

class ParagraphTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Paragraph('name', 'value', array('class' => 'foo'));
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
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do[ku]')
        );
    }

    public function testRenderLabel()
    {
        $this->assertNull($this->field->renderLabel());
    }

    public function testRenderField()
    {
        $this->assertEquals('<p class="foo">value</p>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertNull($this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<p class="foo">value</p>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<p class="foo">value</p>', $this->field->__toString());
    }
}
