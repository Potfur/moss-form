<?php
namespace Moss\Form\field;

use Moss\Form\AbstractFieldTest;
use Moss\Form\Option;

class CheckboxTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Checkbox('name', 'value', 'label', true, array('class' => 'foo'));
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
        $field = '<ul class="foo" id="name">
<li class="options"><input type="checkbox" name="name[]" value="option_1" id="name_option_1" required="1"/><label for="name_option_1" class="inline">Option 1<sup>*</sup></label></li>
<li class="options"><input type="checkbox" name="name[]" value="option_2" id="name_option_2" required="1"/><label for="name_option_2" class="inline">Option 2<sup>*</sup></label></li>
</ul>';
        $this->assertEquals($field, $this->field->renderField());
    }

    public function testRenderNoOptions()
    {
        $field = '<ul class="foo" id="name">
<li><input type="checkbox" name="name[]" value="" id="name_empty"/><label for="name_empty" class="inline">---</label></li>
</ul>';
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
        $field = '<ul class="foo" id="name">
<li class="options"><input type="checkbox" name="name[]" value="option_1" id="name_option_1" required="1"/><label for="name_option_1" class="inline">Option 1<sup>*</sup></label></li>
<li class="options"><input type="checkbox" name="name[]" value="option_2" id="name_option_2" required="1"/><label for="name_option_2" class="inline">Option 2<sup>*</sup></label></li>
</ul>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>' . $field, $this->field->__toString());
    }

    public function testToString()
    {
        $field = '<ul class="foo" id="name">
<li class="options"><input type="checkbox" name="name[]" value="option_1" id="name_option_1" required="1"/><label for="name_option_1" class="inline">Option 1<sup>*</sup></label></li>
<li class="options"><input type="checkbox" name="name[]" value="option_2" id="name_option_2" required="1"/><label for="name_option_2" class="inline">Option 2<sup>*</sup></label></li>
</ul>';
        $this->assertEquals('<label for="name">label<sup>*</sup></label>' . $field, $this->field->__toString());
    }
}
