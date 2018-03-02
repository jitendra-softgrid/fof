<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\GroupedList;
use FOF40\Form\Form;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/GroupedListDataprovider.php';

/**
 * @covers  FOF40\Form\Field\GroupedList::<private>
 * @covers  FOF40\Form\Field\GroupedList::<protected>
 */
class GroupedListTest extends FOFTestCase
{
    /**
     * @group           GroupedList
     * @group           GroupedList__get
     * @covers          FOF40\Form\Field\GroupedList::__get
     * @dataProvider    GroupedListDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\GroupedList')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           GroupedList
     * @group           GroupedListGetStatic
     * @covers          FOF40\Form\Field\GroupedList::getStatic
     * @dataProvider    GroupedListDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\GroupedList')->setMethods(array('getInput', 'getFieldContents'))->getMock();

        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="GroupedList" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getStatic();
    }

    /**
     * @group           GroupedList
     * @group           GroupedListGetRepeatable
     * @covers          FOF40\Form\Field\GroupedList::getRepeatable
     * @dataProvider    GroupedListDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\GroupedList')->setMethods(array('getInput', 'getFieldContents'))->getMock();
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="GroupedList" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getRepeatable();
    }

    /**
     * @group           GroupedList
     * @group           GroupedListGetFieldContents
     * @covers          FOF40\Form\Field\GroupedList::getFieldContents
     * @dataProvider    GroupedListDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'GroupedList::getFieldContents %s - Case: '.$check['case'];

        $field = new GroupedList();
        $data = '
<field name="mylistvalue" type="groupedlist" default="" label="Select an option" description="">
  <group label="Group 1">
    <option value="0">Option 1</option>
    <option value="1">Option 2</option>
  </group>
  <group label="Group 2">
    <option value="3">Option 3</option>
    <option value="4">Option 4</option>
  </group>
  <option value="5">Option 5</option>
  <option value="6">Option 6</option>
</field>';
        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $form = new Form(static::$container, 'Foobar');
        $field->setForm($form);

        // Registered access level
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           GroupedList
     * @group           GroupedListGetOptionName
     * @covers          FOF40\Form\Field\GroupedList::getOptionName
     * @dataProvider    GroupedListDataprovider::getTestGetOptionName
     */
    public function testGetOptionName($test, $check)
    {
        $msg = 'GroupedList::getOptionName %s - Case: '.$check['case'];

        $result = GroupedList::getOptionName($test['data'], $test['selected'], 'items', $test['optKey'], $test['optText']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}