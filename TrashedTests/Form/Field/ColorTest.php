<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\Color;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/ColorDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Color::<private>
 * @covers  FOF40\Form\Field\Color::<protected>
 */
class ColorTest extends FOFTestCase
{
    /**
     * @group           Color
     * @group           Color__get
     * @covers          FOF40\Form\Field\Color::__get
     * @dataProvider    ColorDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Color')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Color
     * @group           ColorGetStatic
     * @covers          FOF40\Form\Field\Color::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Color')->setMethods(array('getInput'))->getMock();

        $field->expects($this->once())->method('getInput');

        $field->getStatic();
    }

    /**
     * @group           Color
     * @group           ColorGetRepeatable
     * @covers          FOF40\Form\Field\Color::getRepeatable
     * @dataProvider    ColorDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Color::getRepeatable %s - Case: '.$check['case'];

        $field = $this->getMockBuilder('FOF40\Form\Field\Color')->setMethods(array('getInput'))->getMock();
        $field->expects($this->exactly($check['input']))->method('getInput');

        $field->id    = 'foo';
        $field->value = $test['value'];
        $field->class = $test['class'];

        $data  = '<field type="Color" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $html = $field->getRepeatable();

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}