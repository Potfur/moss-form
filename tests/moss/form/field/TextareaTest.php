<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;

class TextareaTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Textarea('name', 'value', 'label', true, array('class' => 'foo'));
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
        $this->field->rows(20);
        $this->field->cols(40);
        $this->assertEquals('<textarea name="name" id="name" rows="20" cols="40" class="foo" required="required">value</textarea>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><textarea name="name" id="name" rows="10" cols="20" class="foo" required="required">value</textarea>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><textarea name="name" id="name" rows="10" cols="20" class="foo" required="required">value</textarea>', $this->field->__toString());
    }
}
