<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/HiddenDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Hidden::<private>
 * @covers  FOF40\Form\Field\Hidden::<protected>
 */
class HiddenTest extends FOFTestCase
{
    /**
     * @group           Hidden
     * @group           Hidden__get
     * @covers          FOF40\Form\Field\Hidden::__get
     * @dataProvider    HiddenDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Hidden')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Hidden
     * @group           HiddenGetStatic
     * @covers          FOF40\Form\Field\Hidden::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Hidden')->setMethods(array('getInput'))->getMock();

        $field->expects($this->once())->method('getInput');

        $field->getStatic();
    }

    /**
     * @group           Hidden
     * @group           HiddenGetRepeatable
     * @covers          FOF40\Form\Field\Hidden::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Hidden')->setMethods(array('getInput'))->getMock();
        $field->expects($this->once())->method('getInput');

        $field->getRepeatable();
    }
}
