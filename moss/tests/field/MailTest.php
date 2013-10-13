<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;

class MailTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Mail('name', 'value', 'label', true, array('class' => 'foo'));
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
        $this->assertEquals('<input type="email" name="name" value="value" id="name" class="foo" required="required"/>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><input type="email" name="name" value="value" id="name" class="foo" required="required"/>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><input type="email" name="name" value="value" id="name" class="foo" required="required"/>', $this->field->__toString());
    }
}