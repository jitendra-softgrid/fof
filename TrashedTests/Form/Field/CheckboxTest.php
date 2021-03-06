<?php
/**
 * @package     FOF
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Checkbox;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/CheckboxDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Checkbox::<private>
 * @covers  FOF30\Form\Field\Checkbox::<protected>
 */
class CheckboxTest extends FOFTestCase
{
    /**
     * @group           Checkbox
     * @group           Checkbox__get
     * @covers          FOF30\Form\Field\Checkbox::__get
     * @dataProvider    CheckboxDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF30\Form\Field\Checkbox')->setMethods(array('getStatic', 'getRepeatable'))->getMock();
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Checkbox
     * @group           CheckboxGetStatic
     * @covers          FOF30\Form\Field\Checkbox::getStatic
     * @dataProvider    CheckboxDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMockBuilder('FOF30\Form\Field\Checkbox')->setMethods(array('getInput', 'getFieldContents'))->getMock();

        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Checkbox" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getStatic();
    }

    /**
     * @group           Checkbox
     * @group           CheckboxGetRepeatable
     * @covers          FOF30\Form\Field\Checkbox::getRepeatable
     * @dataProvider    CheckboxDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMockBuilder('FOF30\Form\Field\Checkbox')->setMethods(array('getInput', 'getFieldContents'))->getMock();
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Checkbox" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getRepeatable();
    }

    /**
     * @group           Checkbox
     * @group           CheckboxGetFieldContents
     * @covers          FOF30\Form\Field\Checkbox::getFieldContents
     * @dataProvider    CheckboxDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Checkbox::getFieldContents %s - Case: '.$check['case'];

        $field = new Checkbox();

        $data = '<field type="Checkbox" ';

        if($test['value'])
        {
            $data .= 'value="'.$test['value'].'" ';
        }

        $data .= '/>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        foreach($test['attribs'] as $attrib => $value)
        {
            $field->$attrib = $value;
        }

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
