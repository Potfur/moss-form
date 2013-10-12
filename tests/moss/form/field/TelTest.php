<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;

class telTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Tel('name', 'value', 'label', true, array('class' => 'foo'));
    }

    public function tearDown()
    {
    }

    public function testRenderLabel()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label>', $this->field->renderLabel());
    }

    public function testRenderField()
    {
        $this->assertEquals('<input type="tel" name="name" value="value" id="name" class="foo" required="required"/>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><input type="tel" name="name" value="value" id="name" class="foo" required="required"/>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><input type="tel" name="name" value="value" id="name" class="foo" required="required"/>', $this->field->__toString());
    }
}
