<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;

class HiddenTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Hidden('name', 'value', array('class' => 'foo'));
    }

    public function tearDown()
    {
    }

    public function testIsVisible()
    {
        $this->assertFalse($this->field->isVisible());
    }

    public function testRenderLabel()
    {
        $this->assertNull($this->field->renderLabel());
    }

    public function testRenderField()
    {
        $this->assertEquals('<input type="hidden" name="name" value="value" id="name" class="foo"/>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<input type="hidden" name="name" value="value" id="name" class="foo"/>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<input type="hidden" name="name" value="value" id="name" class="foo"/>', $this->field->__toString());
    }
}
