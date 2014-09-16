<?php
namespace Moss\Form\Field;

class FileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new File('name', array(), array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    public function testIdentifyFromName()
    {
        $field = new File('name', array(), array());
        $this->assertEquals('name', $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new File('name', array(), array());
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
        $field = new File('name', array());
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new File('name', array(), array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new File('name', array(), array());
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
        $field = new File($actual, array(), array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new File(null, array(), array());
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
    public function testValueFromConstructor($actual)
    {
        $field = new File('name', $actual, array());
        $this->assertEquals($actual, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual)
    {
        $field = new File('name', array(), array());
        $this->assertEquals($actual, $field->value($actual));
    }

    public function valueProvider()
    {
        return array(
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 0,
                    'size' => 123
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 1,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 2,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 3,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 4,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 5,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 6,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 7,
                    'size' => 0
                )
            ),
            array(
                array(
                    'name' => 'bar.txt',
                    'type' => 'text/plain',
                    'tmp_name' => 'whatever',
                    'error' => 8,
                    'size' => 0
                )
            )
        );
    }

    public function testError()
    {
        $field = new File('name', array(), array());
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $files = array(
            'name' => 'bar.txt',
            'type' => 'text/plain',
            'tmp_name' => 'whatever',
            'error' => 0,
            'size' => 123
        );

        $field = new File('name', $files, array('required' => true));
        $field->condition($condition, 'Error');
        $this->assertEquals($isValid, $field->isValid());
    }

    public function conditionProvider()
    {
        return array(
            array('/^[a-z\/]+$/', true),
            array('/^[0-9]+$/', false),
            array(array('text/plain'), true),
            array(array(), false),
            array(
                function ($value) {
                    return $value['type'] === 'text/plain';
                },
                true
            ),
            array(
                function ($value) {
                    return $value['type'] !== 'text/plain';
                },
                false,
            ),
            array(true, true),
            array(false, false)
        );
    }

    public function testRequired()
    {
        $field = new File('name', array(), array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new File('name', array(), array());
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new File('name', array(), array('required'));
        $this->assertEquals('<label for="name">name<sup>*</sup></label>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $field = new File('name', array(), array('required'));
        $this->assertEquals('<input type="file" id="name" name="name" required="required"/>', $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new File('name', array(), array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new File('name', array(), array());
        $field->condition(false, 'Error')
            ->validate();

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRender()
    {
        $field = new File('name', array(), array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals('<label for="id">label<sup>*</sup></label><input type="file" id="id" name="name" required="required" class="foo"/>', $field->render());
    }

    public function testToString()
    {
        $field = new File('name', array(), array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}