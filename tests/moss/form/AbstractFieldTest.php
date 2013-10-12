<?php
namespace moss\form;


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
        $this->assertInstanceOf('\moss\form\ErrorsBag', $this->field->errors());
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
            array('/^[a-z]+$/', true),
            array('/^[0-9]+$/', false),
            array(array('value'), true),
            array(array(), false),
            array(
                function ($value) {
                    return $value === 'value';
                }, true
            ),
            array(
                function ($value) {
                    return $value !== 'value';
                }, false
            ),
            array(true, true),
            array(false, false)
        );
    }

    public function testRequired()
    {
        $this->assertFalse($this->field->required(false));
        $this->assertTrue($this->field->required(true));
    }

    public function testAttributes()
    {
        $this->assertInstanceOf('\moss\form\AttributesBag', $this->field->attributes());
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
