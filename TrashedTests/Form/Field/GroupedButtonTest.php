<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\GroupedButton;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once __DIR__ . '/GroupedButtonDataprovider.php';

/**
 * @covers  FOF40\Form\Field\GroupedButton::<private>
 * @covers  FOF40\Form\Field\GroupedButton::<protected>
 */
class GroupedButtonTest extends FOFTestCase
{
    /**
     * @group           GroupedButton
     * @group           GroupedButton__get
     * @covers          FOF40\Form\Field\GroupedButton::__get
     * @dataProvider    GroupedButtonDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\GroupedButton')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           GroupedButton
     * @group           GroupedButtonGetStatic
     * @covers          FOF40\Form\Field\GroupedButton::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\GroupedButton')->setMethods(array('getInput'))->getMock();

        $field->expects($this->once())->method('getInput');

        $field->getStatic();
    }

    /**
     * @group           GroupedButton
     * @group           GroupedButtonGetRepeatable
     * @covers          FOF40\Form\Field\GroupedButton::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\GroupedButton')->setMethods(array('getInput'))->getMock();
        $field->expects($this->once())->method('getInput');

        $field->getRepeatable();
    }

    /**
     * @group           GroupedButton
     * @group           GroupedButtonGetInput
     * @covers          FOF40\Form\Field\GroupedButton::getInput
     */
    public function testGetInput()
    {
        $field = new GroupedButton();

        ReflectionHelper::setValue($field, 'id', 'foo');
        ReflectionHelper::setValue($field, 'class', 'foo-class');

        $data  = '<field type="GroupedButton">';
        $data  .= '<button name="first" />';
        $data  .= '<button name="second" />';
        $data .= '</field>';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName' => '#__foftest_foobars'
        );

        $model = new DataModelStub(static::$container, $config);
        $field->item = $model;

        $result = $field->getInput();

        $expected = '<div id="foo" class="btn-group foo-class"><button id="first" class="btn " ></button><button id="second" class="btn " ></button></div>';

        $this->assertEquals($expected, $result, 'GroupedButton::getInput Returned the wrong value');
    }
}
