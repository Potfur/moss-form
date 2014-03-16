<?php
namespace Moss\Form\field;

use Moss\Form\AbstractFieldTest;

class DateTest extends AbstractFieldTest
{

    public function setUp()
    {
        $this->field = new Date('name', new \DateTime('2013-01-01 11:11:11'), 'label', true, array('class' => 'foo'));
    }

    public function tearDown()
    {
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValue($actual, $expected)
    {
        $this->assertEquals(
             $expected->format('Y-m-d H:i:s'), $this->field->value($actual)
                                                           ->format('Y-m-d H:i:s')
        );
    }

    public function valueProvider()
    {
        return array(
            array(1357038671, new \DateTime('2013-01-01 11:11:11')),
            array(new \DateTime('2013-01-01 11:11:11'), new \DateTime('2013-01-01 11:11:11')),
            array('2013-01-01 11:11:11', new \DateTime('2013-01-01 11:11:11'))
        );
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $this->field->condition($condition, 'Error');
        $this->assertEquals($isValid, $this->field->isValid());
    }

    public function conditionProvider()
    {
        return array(
            array('/^[0-9\-: ]+$/', true),
            array('/^[a-z]+$/', false),
            array(array('2013-01-01 11:11:11'), true),
            array(array(), false),
            array(
                function ($value) {
                    return $value === '2013-01-01 11:11:11';
                }, true
            ),
            array(
                function ($value) {
                    return $value !== '2013-01-01 11:11:11';
                }, false
            ),
            array(true, true),
            array(false, false)
        );
    }

    public function testRenderLabel()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label>', $this->field->renderLabel());
    }

    public function testRenderField()
    {
        $this->assertEquals('<input type="datetime" name="name" value="2013-01-01 11:11:11" id="name" class="foo" required="required"/>', $this->field->renderField());
    }

    public function testRenderError()
    {
        $this->assertEquals('', $this->field->renderError());
    }

    public function testRender()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><input type="datetime" name="name" value="2013-01-01 11:11:11" id="name" class="foo" required="required"/>', $this->field->render());
    }

    public function testToString()
    {
        $this->assertEquals('<label for="name">label<sup>*</sup></label><input type="datetime" name="name" value="2013-01-01 11:11:11" id="name" class="foo" required="required"/>', $this->field->__toString());
    }
}
