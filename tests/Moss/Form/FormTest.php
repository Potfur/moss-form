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


class FormTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $form = new Form('./', 'post', array('id' => $actual));
        $this->assertEquals($expected, $form->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $form = new Form('./', 'post');
        $this->assertEquals($expected, $form->identify($actual));
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
        $form = new Form('./', 'post');
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $form->attributes());
    }

    public function testErrors()
    {
        $form = new Form('./', 'post');
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $form->errors());
    }

    public function testIsVisible()
    {
        $form = new Form('./', 'post');
        $this->assertTrue($form->isVisible());
    }

    public function testEmptyFieldsetIsValid()
    {
        $form = new Form('./', 'post');
        $this->assertTrue($form->isValid());
    }

    public function testIsInvalid()
    {
        $field = $this->getMock('\Moss\Form\FieldInterface');
        $field->expects($this->any())->method('isValid')->will($this->returnValue(false));

        $form = new Form('./', 'post');
        $form->set('field', $field);

        $this->assertFalse($form->isValid());
    }

    public function testPrototype()
    {
        $field = $this->getMock('\Moss\Form\FieldInterface');
        $field->expects($this->any())->method('isVisible')->will($this->returnValue(true));
        $field->expects($this->any())->method('render')->will($this->returnValue('{field}'));

        $form = new Form('./', 'post');
        $form->set('field', $field);

        $this->assertEquals('&lt;form enctype=&quot;multipart/form-data&quot; method=&quot;post&quot; action=&quot;./&quot;&gt;&lt;fieldset&gt;&lt;ul &gt;&lt;li&gt;{field}&lt;/li&gt;&lt;/ul&gt;&lt;/fieldset&gt;&lt;/form&gt;', $form->prototype());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetSet($offset, $value)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertEquals($value, $form->get($offset));
    }

    public function testGetWithDefaultValue()
    {
        $form = new Form('./', 'post');
        $this->assertEquals('bar', $form->get('foo', 'bar'));
    }

    public function testSetArray()
    {
        $form = new Form('./', 'post');
        $form->set(array('foo' => 'bar'));
        $this->assertEquals('bar', $form->get('foo'));
    }

    public function testSetWithoutOffset()
    {
        $form = new Form('./', 'post');
        $form->set('foo', 'foo');
        $form->set(null, 'bar');
        $this->assertEquals('bar', $form->get(0));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetAll($offset, $value, $expected)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertEquals($expected, $form->get());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHasWithoutParam($offset, $value)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertTrue($form->has());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHas($offset, $value)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertTrue($form->has($offset));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAll($offset, $value, $expected)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertEquals($expected, $form->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAllReplace($offset, $value, $expected)
    {
        $form = new Form('./', 'post');
        $form->all(array($offset => $value));
        $this->assertEquals($expected, $form->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemove($offset, $value, $expected, $removed = array())
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertEquals($expected, $form->all());
        $form->remove($offset);
        $this->assertEquals($removed, $form->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemoveAll($offset, $value, $expected)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertEquals($expected, $form->all());
        $form->remove();
        $this->assertEquals(array(), $form->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testReset($offset, $value, $expected)
    {
        $form = new Form('./', 'post');
        $form->set($offset, $value);
        $this->assertEquals($expected, $form->all());
        $form->reset();
        $this->assertEquals(array(), $form->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetUnset($offset, $value)
    {
        $form = new Form('./', 'post');
        $form[$offset] = $value;
        unset($form[$offset]);
        $this->assertEquals(0, $form->count());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetGetSet($offset, $value)
    {
        $form = new Form('./', 'post');
        $form[$offset] = $value;
        $this->assertEquals($value, $form[$offset]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetGetEmpty($offset)
    {
        $form = new Form('./', 'post');
        $this->assertNull(null, $form[$offset]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetSetWithoutKey($value)
    {
        $form = new Form('./', 'post');
        $form[] = $value;
        $this->assertEquals($value, $form[0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetExists($offset, $value)
    {
        $form = new Form('./', 'post');
        $form[$offset] = $value;
        $this->assertTrue(isset($form[$offset]));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIterator($offset, $value)
    {
        $form = new Form('./', 'post');
        $form[$offset] = $value;

        foreach ($form as $key => $val) {
            $this->assertEquals($key, $offset);
            $this->assertEquals($val, $value);
        }
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCount($offset, $value)
    {
        $form = new Form('./', 'post');
        $form[1] = $offset;
        $form[2] = $value;
        $this->assertEquals(2, $form->count());
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

        $form = new Form('./', 'post', array('class' => array('foo', 'bar')));
        $form->set('hidden', $hidden)->set('visible', $visible);

        $this->assertEquals('<form enctype="multipart/form-data" method="post" action="./" class="foo bar"><fieldset>{hidden}<ul class="foo bar"><li>{visible}</li></ul></fieldset></form>', $form->render());
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

        $form = new Form('./', 'post', array('class' => array('foo', 'bar')));
        $form->set('hidden', $hidden)->set('visible', $visible);

        $this->assertEquals($form->render(), (string) $form);
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

        $form = new Form('./', 'post', array('class' => array('foo', 'bar')));
        $form->set('visible', $field)
            ->groupTag('div')
            ->elementTag('span');

        $this->assertEquals('<form enctype="multipart/form-data" method="post" action="./" class="foo bar"><fieldset><div class="foo bar"><span>{visible}</span></div></fieldset></form>', $form->render());
    }
}
