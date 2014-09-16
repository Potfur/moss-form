<?php
namespace Moss\Form\Bag;


class ErrorBagTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testGetSet($offset, $value)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertEquals($value, $bag->get($offset));
    }

    public function testGetWithDefaultValue()
    {
        $bag = new ErrorBag();
        $this->assertEquals('bar', $bag->get('foo', 'bar'));
    }

    public function testSetArray()
    {
        $bag = new ErrorBag();
        $bag->set(array('foo' => 'bar'));
        $this->assertEquals('bar', $bag->get('foo'));
    }

    public function testSetWithoutOffset()
    {
        $bag = new ErrorBag();
        $bag->set('foo', 'foo');
        $bag->set(null, 'bar');
        $this->assertEquals('bar', $bag->get(0));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetAll($offset, $value, $expected)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertEquals($expected, $bag->get());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHasWithoutParam($offset, $value)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertTrue($bag->has());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHas($offset, $value)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertTrue($bag->has($offset));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAll($offset, $value, $expected)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertEquals($expected, $bag->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAllReplace($offset, $value, $expected)
    {
        $bag = new ErrorBag();
        $bag->all(array($offset => $value));
        $this->assertEquals($expected, $bag->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemove($offset, $value, $expected, $removed = array())
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertEquals($expected, $bag->all());
        $bag->remove($offset);
        $this->assertEquals($removed, $bag->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemoveAll($offset, $value, $expected)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertEquals($expected, $bag->all());
        $bag->remove();
        $this->assertEquals(array(), $bag->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testReset($offset, $value, $expected)
    {
        $bag = new ErrorBag();
        $bag->set($offset, $value);
        $this->assertEquals($expected, $bag->all());
        $bag->reset();
        $this->assertEquals(array(), $bag->all());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetUnset($offset, $value)
    {
        $bag = new ErrorBag();
        $bag[$offset] = $value;
        unset($bag[$offset]);
        $this->assertEquals(0, $bag->count());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetGetSet($offset, $value)
    {
        $bag = new ErrorBag();
        $bag[$offset] = $value;
        $this->assertEquals($value, $bag[$offset]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetGetEmpty($offset)
    {
        $bag = new ErrorBag();
        $this->assertNull(null, $bag[$offset]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetSetWithoutKey($value)
    {
        $bag = new ErrorBag();
        $bag[] = $value;
        $this->assertEquals($value, $bag[0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testOffsetExists($offset, $value)
    {
        $bag = new ErrorBag();
        $bag[$offset] = $value;
        $this->assertTrue(isset($bag[$offset]));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIterator($offset, $value)
    {
        $bag = new ErrorBag();
        $bag[$offset] = $value;

        foreach ($bag as $key => $val) {
            $this->assertEquals($key, $offset);
            $this->assertEquals($val, $value);
        }
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCount($offset, $value)
    {
        $bag = new ErrorBag();
        $bag[1] = $offset;
        $bag[2] = $value;
        $this->assertEquals(2, $bag->count());
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
        $bag = new ErrorBag();
        $bag->set('condition1', 'Error msg1')
            ->set('condition2', 'Error msg2');
        $this->assertEquals('<ul class="error"><li>Error msg1</li><li>Error msg2</li></ul>', $bag->render());
    }

    public function testToString()
    {
        $bag = new ErrorBag();
        $bag->set('condition1', 'Error msg1')
            ->set('condition2', 'Error msg2');
        $this->assertEquals('<ul class="error"><li>Error msg1</li><li>Error msg2</li></ul>', (string) $bag);
    }

}
 