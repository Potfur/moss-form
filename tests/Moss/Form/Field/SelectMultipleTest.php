<?php
namespace Moss\Form\field;

use Moss\Form\AbstractFieldTest;
use Moss\Form\Option;

class SelectMultipleTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new SelectMultiple('name', 'value', 'label', true, array('class' => 'foo'));
        $this->field->options()
                    ->set(array(new Option('Option 1', 'option_1'), new Option('Option 2', 'option_2')));
    }

    public function tearDown()
    {
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValue($actual, $expected)
    {
        $this->assertEquals($expected, $this->field->value($actual));
    }

    public function valueProvider()
    {
        return array(
            array('foo', array('foo')),
            array('Bar', array('Bar')),
            array('yada yada', array('yada yada')),
            array('do[ku]', array('do[ku]'))
        );
    }

    public function testRenderLabel()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label>', $this->field->renderLabel());
    }

    public function testRenderField()
    {
        $field = '<select name="name" id="name" multiple="multiple" class="foo" required="required">
<option value="option_1" id="name_option_1" >Option 1</option>
<option value="option_2" id="name_option_2" >Option 2</option>
</select>';
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderNoOptions()
    {
        $field = '<select name="name" id="name" multiple="multiple" class="foo" required="required">
<option value="" id="name_empty">---</option>
</select>';
        $this->field->options()
                    ->set(array());
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $field = '<select name="name" id="name" multiple="multiple" class="foo" required="required">
<option value="option_1" id="name_option_1" >Option 1</option>
<option value="option_2" id="name_option_2" >Option 2</option>
</select>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>' . $field, $this->field->__toString());
    }

    public function testToString()
    {
        $field = '<select name="name" id="name" multiple="multiple" class="foo" required="required">
<option value="option_1" id="name_option_1" >Option 1</option>
<option value="option_2" id="name_option_2" >Option 2</option>
</select>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>' . $field, $this->field->__toString());
    }
}
