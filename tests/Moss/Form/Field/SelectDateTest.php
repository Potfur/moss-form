<?php
namespace Moss\Form\Field;

class SelectDateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromConstructor($actual, $expected)
    {
        $field = new SelectDate('name', new \DateTime, array('id' => $actual));
        $this->assertEquals($expected, $field->identify());
    }

    public function testIdentifyFromName()
    {
        $field = new SelectDate('name', new \DateTime, array());
        $this->assertEquals('name', $field->identify());
    }

    /**
     * @dataProvider identifyProvider
     */
    public function testIdentifyFromMethod($actual, $expected)
    {
        $field = new SelectDate('name', new \DateTime, array());
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
        $field = new SelectDate('name', new \DateTime);
        $this->assertTrue($field->isVisible());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromConstructor($actual, $expected)
    {
        $field = new SelectDate('name', new \DateTime, array('label' => $actual));
        $this->assertEquals($expected, $field->label());
    }

    /**
     * @dataProvider labelProvider
     */
    public function testLabelFromMethod($actual, $expected)
    {
        $field = new SelectDate('name', new \DateTime, array());
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
        $field = new SelectDate($actual, new \DateTime, array());
        $this->assertEquals($expected, $field->name());
    }

    /**
     * @dataProvider nameProvider
     */
    public function testNameFromMethod($actual, $expected)
    {
        $field = new SelectDate(null, new \DateTime, array());
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
        $field = new SelectDate('name', $actual, array());
        $this->assertEquals($expected, $field->value());
    }

    /**
     * @dataProvider valueProvider
     */
    public function testValueFromMethod($actual, $expected)
    {
        $field = new SelectDate('name', null, array());
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
        $field = new SelectDate('name', new \DateTime, array());
        $this->assertInstanceOf('\Moss\Form\Bag\ErrorBag', $field->errors());
    }

    /**
     * @dataProvider conditionProvider
     */
    public function testCondition($condition, $isValid)
    {
        $field = new SelectDate('name', new \DateTime('@1399381053'), array('required' => true));
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
        $field = new SelectDate('name', new \DateTime, array());
        $this->assertFalse($field->required(false));
        $this->assertTrue($field->required(true));
    }

    public function testAttributes()
    {
        $field = new SelectDate('name', new \DateTime, array());
        $this->assertInstanceOf('\Moss\Form\Bag\AttributeBag', $field->attributes());
    }

    public function testRenderLabel()
    {
        $field = new SelectDate('name', new \DateTime, array('required'));
        $this->assertEquals('<label for="name">name<sup>*</sup></label>', $field->renderLabel());
    }

    public function testRenderField()
    {
        $expected = '<ul id="name" class=""><li><select name="name[year]" class="date year small"><option value="1989" >1989</option><option value="1990" >1990</option><option value="1991" >1991</option><option value="1992" >1992</option><option value="1993" >1993</option><option value="1994" >1994</option><option value="1995" >1995</option><option value="1996" >1996</option><option value="1997" >1997</option><option value="1998" >1998</option><option value="1999" >1999</option><option value="2000" >2000</option><option value="2001" >2001</option><option value="2002" >2002</option><option value="2003" >2003</option><option value="2004" >2004</option><option value="2005" >2005</option><option value="2006" >2006</option><option value="2007" >2007</option><option value="2008" >2008</option><option value="2009" >2009</option><option value="2010" >2010</option><option value="2011" >2011</option><option value="2012" >2012</option><option value="2013" >2013</option><option value="2014" selected="selected">2014</option><option value="2015" >2015</option><option value="2016" >2016</option><option value="2017" >2017</option><option value="2018" >2018</option><option value="2019" >2019</option><option value="2020" >2020</option><option value="2021" >2021</option><option value="2022" >2022</option><option value="2023" >2023</option><option value="2024" >2024</option><option value="2025" >2025</option><option value="2026" >2026</option><option value="2027" >2027</option><option value="2028" >2028</option><option value="2029" >2029</option><option value="2030" >2030</option><option value="2031" >2031</option><option value="2032" >2032</option><option value="2033" >2033</option><option value="2034" >2034</option><option value="2035" >2035</option><option value="2036" >2036</option><option value="2037" >2037</option><option value="2038" >2038</option><option value="2039" >2039</option></select></li><li>-</li><li><select name="name[month]" class="date month small"><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" selected="selected">05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option></select></li><li>-</li><li><select name="name[day]" class="date day small"><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" selected="selected">06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option></select></li> <li><select name="name[hour]" class="date hour small"><option value="0" >00</option><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" selected="selected">12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option></select></li><li>:</li><li><select name="name[minute]" class="date minute small"><option value="0" >00</option><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option><option value="32" >32</option><option value="33" >33</option><option value="34" >34</option><option value="35" >35</option><option value="36" >36</option><option value="37" >37</option><option value="38" >38</option><option value="39" >39</option><option value="40" >40</option><option value="41" >41</option><option value="42" >42</option><option value="43" >43</option><option value="44" >44</option><option value="45" >45</option><option value="46" >46</option><option value="47" >47</option><option value="48" >48</option><option value="49" >49</option><option value="50" >50</option><option value="51" >51</option><option value="52" >52</option><option value="53" >53</option><option value="54" >54</option><option value="55" >55</option><option value="56" >56</option><option value="57" selected="selected">57</option><option value="58" >58</option><option value="59" >59</option></select></li><li>:</li><li><select name="name[second]" class="date second small"><option value="0" >00</option><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option><option value="32" >32</option><option value="33" selected="selected">33</option><option value="34" >34</option><option value="35" >35</option><option value="36" >36</option><option value="37" >37</option><option value="38" >38</option><option value="39" >39</option><option value="40" >40</option><option value="41" >41</option><option value="42" >42</option><option value="43" >43</option><option value="44" >44</option><option value="45" >45</option><option value="46" >46</option><option value="47" >47</option><option value="48" >48</option><option value="49" >49</option><option value="50" >50</option><option value="51" >51</option><option value="52" >52</option><option value="53" >53</option><option value="54" >54</option><option value="55" >55</option><option value="56" >56</option><option value="57" >57</option><option value="58" >58</option><option value="59" >59</option></select></li></ul>';

        $field = new SelectDate('name', new \DateTime('@1399381053'), array('required'));
        $this->assertEquals($expected, $field->renderField());
    }

    public function testRenderErrorWithoutErrors()
    {
        $field = new SelectDate('name', new \DateTime, array());
        $this->assertEquals('', $field->renderError());
    }

    public function testRenderErrorWithErrors()
    {
        $field = new SelectDate('name', new \DateTime, array());
        $field->condition(false, 'Error')
            ->validate();

        $this->assertEquals('<ul class="error"><li>Error</li></ul>', $field->renderError());
    }

    public function testRender()
    {
        $expected = '<label for="id">label<sup>*</sup></label><ul id="id" class="foo"><li><select name="name[year]" class="date year small"><option value="1989" >1989</option><option value="1990" >1990</option><option value="1991" >1991</option><option value="1992" >1992</option><option value="1993" >1993</option><option value="1994" >1994</option><option value="1995" >1995</option><option value="1996" >1996</option><option value="1997" >1997</option><option value="1998" >1998</option><option value="1999" >1999</option><option value="2000" >2000</option><option value="2001" >2001</option><option value="2002" >2002</option><option value="2003" >2003</option><option value="2004" >2004</option><option value="2005" >2005</option><option value="2006" >2006</option><option value="2007" >2007</option><option value="2008" >2008</option><option value="2009" >2009</option><option value="2010" >2010</option><option value="2011" >2011</option><option value="2012" >2012</option><option value="2013" >2013</option><option value="2014" selected="selected">2014</option><option value="2015" >2015</option><option value="2016" >2016</option><option value="2017" >2017</option><option value="2018" >2018</option><option value="2019" >2019</option><option value="2020" >2020</option><option value="2021" >2021</option><option value="2022" >2022</option><option value="2023" >2023</option><option value="2024" >2024</option><option value="2025" >2025</option><option value="2026" >2026</option><option value="2027" >2027</option><option value="2028" >2028</option><option value="2029" >2029</option><option value="2030" >2030</option><option value="2031" >2031</option><option value="2032" >2032</option><option value="2033" >2033</option><option value="2034" >2034</option><option value="2035" >2035</option><option value="2036" >2036</option><option value="2037" >2037</option><option value="2038" >2038</option><option value="2039" >2039</option></select></li><li>-</li><li><select name="name[month]" class="date month small"><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" selected="selected">05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option></select></li><li>-</li><li><select name="name[day]" class="date day small"><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" selected="selected">06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option></select></li> <li><select name="name[hour]" class="date hour small"><option value="0" >00</option><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" selected="selected">12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option></select></li><li>:</li><li><select name="name[minute]" class="date minute small"><option value="0" >00</option><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option><option value="32" >32</option><option value="33" >33</option><option value="34" >34</option><option value="35" >35</option><option value="36" >36</option><option value="37" >37</option><option value="38" >38</option><option value="39" >39</option><option value="40" >40</option><option value="41" >41</option><option value="42" >42</option><option value="43" >43</option><option value="44" >44</option><option value="45" >45</option><option value="46" >46</option><option value="47" >47</option><option value="48" >48</option><option value="49" >49</option><option value="50" >50</option><option value="51" >51</option><option value="52" >52</option><option value="53" >53</option><option value="54" >54</option><option value="55" >55</option><option value="56" >56</option><option value="57" selected="selected">57</option><option value="58" >58</option><option value="59" >59</option></select></li><li>:</li><li><select name="name[second]" class="date second small"><option value="0" >00</option><option value="1" >01</option><option value="2" >02</option><option value="3" >03</option><option value="4" >04</option><option value="5" >05</option><option value="6" >06</option><option value="7" >07</option><option value="8" >08</option><option value="9" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" >14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option><option value="24" >24</option><option value="25" >25</option><option value="26" >26</option><option value="27" >27</option><option value="28" >28</option><option value="29" >29</option><option value="30" >30</option><option value="31" >31</option><option value="32" >32</option><option value="33" selected="selected">33</option><option value="34" >34</option><option value="35" >35</option><option value="36" >36</option><option value="37" >37</option><option value="38" >38</option><option value="39" >39</option><option value="40" >40</option><option value="41" >41</option><option value="42" >42</option><option value="43" >43</option><option value="44" >44</option><option value="45" >45</option><option value="46" >46</option><option value="47" >47</option><option value="48" >48</option><option value="49" >49</option><option value="50" >50</option><option value="51" >51</option><option value="52" >52</option><option value="53" >53</option><option value="54" >54</option><option value="55" >55</option><option value="56" >56</option><option value="57" >57</option><option value="58" >58</option><option value="59" >59</option></select></li></ul>';

        $field = new SelectDate('name', new \DateTime('@1399381053'), array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals($expected, $field->render());
    }

    public function testToString()
    {
        $field = new SelectDate('name', new \DateTime, array('id' => 'id', 'label' => 'label', 'required', 'class' => array('foo')));
        $this->assertEquals($field->render(), $field->__toString());
    }
}