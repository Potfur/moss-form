<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;
use moss\form\Option;

class SelectTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Select('name', 'value', 'label', true, array('class' => 'foo'));
        $this->field->options()->set(array(new Option('Option 1'), new Option('Option 2')));
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
        $field = '<select name="name" id="name" class="foo" required="required">
<option value="Option 1" id="name_option_1" >Option 1</option>
<option value="Option 2" id="name_option_2" >Option 2</option>
</select>';
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderNoOptions()
    {
        $field = '<select name="name" id="name" class="foo" required="required">
<option value="" id="name_empty">---</option>
</select>';
        $this->field->options()->set(array());
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $field = '<select name="name" id="name" class="foo" required="required">
<option value="Option 1" id="name_option_1" >Option 1</option>
<option value="Option 2" id="name_option_2" >Option 2</option>
</select>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>'.$field, $this->field->__toString());
    }

    public function testToString()
    {
        $field = '<select name="name" id="name" class="foo" required="required">
<option value="Option 1" id="name_option_1" >Option 1</option>
<option value="Option 2" id="name_option_2" >Option 2</option>
</select>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>'.$field, $this->field->__toString());
    }
}
