<?php
namespace Moss\Form\Field;

class DateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new Date('name', new \DateTime, array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    public function testIdentifyFromName()
    {
        $field = new Date('name', new \DateTime);
        $this->assertEquals('name', $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new Date('name', new \DateTime, array());
        $this->assertEquals($expected, $field->identify($actual));
    }

    public function identifyProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do_ku')
        );
    }

    public function testIsVisible()
    {
        $field = new Date('name', new \DateTime);
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new Date('name', new \DateTime, array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new Date('name', new \DateTime, array());
        $this->assertEquals($expected, $field->label($actual));
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
    public function testNameFromConstructor($actual, $expected)
    {
        $field = new Date($actual, new \DateTime, array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new Date(null, new \DateTime, array());
        $this->assertEquals($expected, $field->name($actual));
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
    public function testValueFromConstructor($actual, $expected)
    {
        $field = new Date('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new Date('name', null, array());
        $this->assertEquals($expected, $field->value($actual));
    }

    public function valueProvider()
    {
        $dateTime = new \DateTime();

        return array(
            array($dateTime, $dateTime),
        );
    }

    public function testError()
    {
        $field = new Date('name', new \DateTime, array());
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $field = new Date('name', new \DateTime('@1399381053'), array('required' => true));
        $field->condition($condition, 'Error');
        $this->assertEquals($isValid, $field->isValid());
    }

    public function conditionProvider()
    {
        $date = new \DateTime('@1399381053');
        $date = $date->format('Y-m-d H:i:s');

        return array(
            array('/^[0-9\- :]+$/', true),
            array('/^[a-z]+$/', false),
            array(array($date), true),
            array(array(), false),
            array(
                function ($value) use ($date) {
                    return $value == $date;
                },
                true
            ),
            array(
                function ($value) use ($date) {
                    return $value != $date;
                },
                false,
            ),
            array(true, true),
            array(false, false)
        );
    }

    public function testRequired()
    {
        $field = new Date('name', new \DateTime, array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new Date('name', new \DateTime, array());
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new Date('name', new \DateTime, array('required'));
        $this->assertEquals('<label for="name">name<sup>*</sup></label>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new Date('name', new \DateTime('@1399381053'), array('required'));
        $this->assertEquals('<input type="datetime" id="name" name="name" value="2014-05-06 12:57:33" required="required"/>', $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new Date('name', new \DateTime, array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new Date('name', new \DateTime, array());
        $field->condition(false, 'Error')
            ->validate();

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRender()
    {
        $field = new Date('name', new \DateTime('@1399381053'), array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals('<label for="id">label<sup>*</sup></label><input type="datetime" id="id" name="name" value="2014-05-06 12:57:33" required="required" class="foo"/>', $field->render());
    }

    public function testToString()
    {
        $field = new Date('name', new \DateTime, array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}