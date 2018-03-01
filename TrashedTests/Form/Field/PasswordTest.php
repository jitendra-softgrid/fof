<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\Password;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/PasswordDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Password::<private>
 * @covers  FOF40\Form\Field\Password::<protected>
 */
class PasswordTest extends FOFTestCase
{
    /**
     * @group           Password
     * @group           Password__get
     * @covers          FOF40\Form\Field\Password::__get
     * @dataProvider    PasswordDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Password')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Password
     * @group           PasswordGetStatic
     * @covers          FOF40\Form\Field\Password::getStatic
     * @dataProvider    PasswordDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Password')->setMethods(array('getInput', 'getFieldContents'))->getMock();

        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Password" name="foobar" ';

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
     * @group           Password
     * @group           PasswordGetRepeatable
     * @covers          FOF40\Form\Field\Password::getRepeatable
     * @dataProvider    PasswordDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Password')->setMethods(array('getInput', 'getFieldContents'))->getMock();
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Password" name="foobar" ';

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
     * @group           Password
     * @group           PasswordGetFieldContents
     * @covers          FOF40\Form\Field\Password::getFieldContents
     * @dataProvider    PasswordDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Password::getFieldContents %s - Case: '.$check['case'];

        $field = new Password();

        // Registered access level
        $field->value = 'password';

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
