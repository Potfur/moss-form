<?php
namespace Moss\Form;


abstract class AbstractFieldTest extends \PHPUnit_Framework_TestCase
{

    /** @var FieldInterface */
    protected $field;

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentify($actual, $expected)
    {
        $this->assertEquals($expected, $this->field->identify($actual));
    }

    public function identifyProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do_ku'),
            array(null, 'name')
        );
    }

    public function testIsVisible()
    {
        $this->assertTrue($this->field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabel($actual, $expected)
    {
        $this->assertEquals($expected, $this->field->label($actual));
    }

    public function labelProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'Bar'),
            array('yada yada', 'yada yada'),
            array('do[ku]', 'do[ku]')
        );
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
            array('foo', 'foo'),
            array('Bar', 'Bar'),
            array('yada yada', 'yada yada'),
            array('do[ku]', 'do[ku]')
        );
    }

    public function testError()
    {
        $this->assertInstanceOf('\Moss\Form\ErrorsBag', $this->field->errors());
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
            array('/^[a-z]+$/', true, false),
            array('/^[0-9]+$/', false, false),
            array(array('value'), true, false),
            array(array(), false, false),
            array(
                function ($value) {
                    return $value === 'value';
                }, true, false
            ),
            array(
                function ($value) {
                    return $value !== 'value';
                }, false, false
            ),
            array(true, true, false),
            array(false, false, false),
            array(true, true, true),
            array(false, false, true),
        );
    }

    public function testRequired()
    {
        $this->assertFalse($this->field->required(false));
        $this->assertTrue($this->field->required(true));
    }

    public function testAttributes()
    {
        $this->assertInstanceOf('\Moss\Form\AttributesBag', $this->field->attributes());
    }

    public function testRenderLabel()
    {
        $this->markTestIncomplete();
    }

    public function testRenderField()
    {
        $this->markTestIncomplete();
    }

    public function testRenderError()
    {
        $this->markTestIncomplete();
    }

    public function testRender()
    {
        $this->markTestIncomplete();
    }

    public function testToString()
    {
        $this->markTestIncomplete();
    }
}
