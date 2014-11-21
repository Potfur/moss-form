<?php

/*
* This file is part of the moss-form package
*
* (c) Michal Wachowski <wachowski.michal@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Moss\Form;


class FieldsetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $fieldset = new Fieldset('Google it', array(), array('id' => $actual));
        $this->assertEquals($expected, $fieldset->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $fieldset = new Fieldset('Google it');
        $this->assertEquals($expected, $fieldset->identify($actual));
    }

    public function identifyProvider()
    {
        return array(
            array('foo', 'foo'),
            array('Bar', 'bar'),
            array('yada yada', 'yada_yada'),
            array('do[ku]', 'do_ku'),
        );
    }

    public function testAttribute()
    {
        $fieldset = new Fieldset();
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $fieldset->attributes());
    }

    public function testErrors()
    {
        $fieldset = new Fieldset();
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $fieldset->errors());
    }

    public function testIsVisible()
    {
        $fieldset = new Fieldset();
        $this->assertTrue($fieldset->isVisible());
    }

    public function testEmptyFieldsetIsValid()
    {
        $fieldset = new Fieldset();
        $this->assertTrue($fieldset->isValid());
    }

    public function testIsInvalid()
    {
        $field = $this->getMock('\Moss\Form\FieldInterface');
        $field->expects($this->any())->method('isValid')->will($this->returnValue(false));

        $fieldset = new Fieldset();
        $fieldset->set('field', $field);

        $this->assertFalse($fieldset->isValid());
    }

    public function testPrototype()
    {
        $field = $this->getMock('\Moss\Form\FieldInterface');
        $field->expects($this->any())->method('isVisible')->will($this->returnValue(true));
        $field->expects($this->any())->method('render')->will($this->returnValue('{field}'));

        $fieldset = new Fieldset();
        $fieldset->set('field', $field);

        $this->assertEquals('&lt;ul &gt;&lt;li&gt;{field}&lt;/li&gt;&lt;/ul&gt;', $fieldset->prototype());
    }

    public function testGetSet()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertEquals('bar', $fieldset->get('foo'));
    }

    public function testGetWithDefaultValue()
    {
        $fieldset = new Fieldset();
        $this->assertEquals('bar', $fieldset->get('foo', 'bar'));
    }

    public function testSetArray()
    {
        $fieldset = new Fieldset();
        $fieldset->set(array('foo' => 'bar'));
        $this->assertEquals('bar', $fieldset->get('foo'));
    }

    public function testSetWithoutOffset()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'foo');
        $fieldset->set(null, 'bar');
        $this->assertEquals('bar', $fieldset->get(0));
    }

    public function testGetAll()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertEquals(array('foo' => 'bar'), $fieldset->get());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHasWithoutParam()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertTrue($fieldset->has());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHas()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertTrue($fieldset->has('foo'));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAll()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertEquals(array('foo' => 'bar'), $fieldset->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAllReplace()
    {
        $fieldset = new Fieldset();
        $fieldset->all(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => 'bar'), $fieldset->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemove()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertEquals(array('foo' => 'bar'), $fieldset->all());
        $fieldset->remove('foo');
        $this->assertEquals(array(), $fieldset->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemoveAll()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertEquals(array('foo' => 'bar'), $fieldset->all());
        $fieldset->remove();
        $this->assertEquals(array(), $fieldset->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testReset()
    {
        $fieldset = new Fieldset();
        $fieldset->set('foo', 'bar');
        $this->assertEquals(array('foo' => 'bar'), $fieldset->all());
        $fieldset->reset();
        $this->assertEquals(array(), $fieldset->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetUnset()
    {
        $fieldset = new Fieldset();
        $fieldset['foo'] = 'bar';
        unset($fieldset['foo']);
        $this->assertEquals(0, $fieldset->count());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetGetSet()
    {
        $fieldset = new Fieldset();
        $fieldset['foo'] = 'bar';
        $this->assertEquals('bar', $fieldset['foo']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetGetEmpty()
    {
        $fieldset = new Fieldset();
        $this->assertNull(null, $fieldset['foo']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetSetWithoutKey()
    {
        $fieldset = new Fieldset();
        $fieldset[] = 'bar';
        $this->assertEquals('bar', $fieldset[0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetExists()
    {
        $fieldset = new Fieldset();
        $fieldset['foo'] = 'bar';
        $this->assertTrue(isset($fieldset['foo']));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIterator()
    {
        $fieldset = new Fieldset();
        $fieldset['foo'] = 'bar';

        foreach ($fieldset as $key => $val) {
            $this->assertEquals($key, 'foo');
            $this->assertEquals($val, 'bar');
        }
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCount()
    {
        $fieldset = new Fieldset();
        $fieldset[1] = 'foo';
        $fieldset[2] = 'bar';
        $this->assertEquals(2, $fieldset->count());
    }

    public function dataProvider()
    {
        return array(
            array('id', 1, array('id' => 1)),
            array('name', 'bar', array('name' => 'bar')),
            array('class', array('lorem', 'ipsum'), array('class' => array('lorem', 'ipsum'))),
        );
    }

    public function testRender()
    {
        $hidden = $this->getMock('\Moss\Form\FieldInterface');

        $hidden->expects($this->any())
            ->method('isVisible')
            ->will($this->returnValue(false));

        $hidden->expects($this->any())
            ->method('render')
            ->will($this->returnValue('{hidden}'));

        $visible = $this->getMock('\Moss\Form\FieldInterface');

        $visible->expects($this->any())
            ->method('isVisible')
            ->will($this->returnValue(true));

        $visible->expects($this->any())
            ->method('render')
            ->will($this->returnValue('{visible}'));

        $fieldset = new Fieldset('Label', array($hidden, $visible), array('class' => array('foo', 'bar')));

        $this->assertEquals('<legend>Label</legend>{hidden}<ul class="foo bar"><li>{visible}</li></ul>', $fieldset->render());
    }

    public function testToString()
    {
        $hidden = $this->getMock('\Moss\Form\FieldInterface');

        $hidden->expects($this->any())
            ->method('isVisible')
            ->will($this->returnValue(false));

        $hidden->expects($this->any())
            ->method('render')
            ->will($this->returnValue('{hidden}'));

        $visible = $this->getMock('\Moss\Form\FieldInterface');

        $visible->expects($this->any())
            ->method('isVisible')
            ->will($this->returnValue(true));

        $visible->expects($this->any())
            ->method('render')
            ->will($this->returnValue('{visible}'));

        $fieldset = new Fieldset('Label', array($hidden, $visible), array('class' => array('foo', 'bar')));

        $this->assertEquals($fieldset->render(), (string) $fieldset);
    }

    public function testRenderWithDifferentTags()
    {
        $field = $this->getMock('\Moss\Form\FieldInterface');

        $field->expects($this->any())
            ->method('isVisible')
            ->will($this->returnValue(true));

        $field->expects($this->any())
            ->method('render')
            ->will($this->returnValue('{visible}'));

        $fieldset = new Fieldset('Label', array($field), array('class' => array('foo', 'bar')));
        $fieldset->groupTag('div')
            ->elementTag('span');

        $this->assertEquals('<legend>Label</legend><div class="foo bar"><span>{visible}</span></div>', $fieldset->render());
    }
}
