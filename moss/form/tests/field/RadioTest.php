<?php
namespace moss\form\field;

use moss\form\AbstractFieldTest;
use moss\form\Option;

class RadioTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Radio('name', 'value', 'label', true, array('class' => 'foo'));
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
        $field = '<ul class="foo" id="name">
<li class="options"><input type="radio" name="name" value="Option 1" id="name_option_1" /><label for="name_option_1" class="inline">Option 1</label></li>
<li class="options"><input type="radio" name="name" value="Option 2" id="name_option_2" /><label for="name_option_2" class="inline">Option 2</label></li>
</ul>';
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderNoOptions()
    {
        $field = '<ul class="foo" id="name">
<li><input type="radio" name="name" value="" id="name_empty"/><label for="name_empty" class="inline">---</label></li>
</ul>';
        $this->field->options()->set(array());
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $field = '<ul class="foo" id="name">
<li class="options"><input type="radio" name="name" value="Option 1" id="name_option_1" /><label for="name_option_1" class="inline">Option 1</label></li>
<li class="options"><input type="radio" name="name" value="Option 2" id="name_option_2" /><label for="name_option_2" class="inline">Option 2</label></li>
</ul>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>'.$field, $this->field->__toString());
    }

    public function testToString()
    {
        $field = '<ul class="foo" id="name">
<li class="options"><input type="radio" name="name" value="Option 1" id="name_option_1" /><label for="name_option_1" class="inline">Option 1</label></li>
<li class="options"><input type="radio" name="name" value="Option 2" id="name_option_2" /><label for="name_option_2" class="inline">Option 2</label></li>
</ul>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>'.$field, $this->field->__toString());
    }
}
