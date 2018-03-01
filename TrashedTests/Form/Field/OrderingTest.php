<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\Ordering;
use FOF40\Form\Form;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once __DIR__ . '/OrderingDataprovider.php';

/**
 * @covers  \FOF40\Form\Field\Ordering::<private>
 * @covers  \FOF40\Form\Field\Ordering::<protected>
 */
class OrderingTest extends DatabaseTest
{
    /**
     * @group           OrderingField
     * @group           Ordering__get
     * @covers          \FOF40\Form\Field\Ordering::__get
     * @dataProvider    \FOF40\Tests\Form\Field\OrderingDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Ordering')
            ->setMethods(array('getStatic', 'getRepeatable'))
            ->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           OrderingField
     * @group           OrderingGetInput
     * @covers          \FOF40\Form\Field\Ordering::getInput
     * @dataProvider    \FOF40\Tests\Form\Field\OrderingDataprovider::getTestGetInput
     */
    public function testGetInput($test, $check)
    {
        $msg = 'Ordering::getInput %s - Case: '.$check['case'];

        $field = new Ordering();

        foreach($test['properties'] as $key => $value)
        {
            ReflectionHelper::setValue($field, $key, $value);
        }

        $data = '<field type="Ordering" ';

        foreach($test['attributes'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';
        $xml   = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $config = array(
            'tableName' => '#__foftest_foobars',
            'idFieldName' => 'foftest_foobar_id'
        );

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');

        $model->find(1);

        $form->setModel($model);
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'getInput');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           OrderingField
     * @group           OrderingGetStatic
     * @covers          \FOF40\Form\Field\Ordering::getStatic
     */
    public function testGetStatic()
    {
        $this->setExpectedException('FOF40\Form\Exception\GetStaticNotAllowed');

        $field = new Ordering();

        $field->getStatic();
    }

    /**
     * @group           OrderingField
     * @group           OrderingGetRepeatable
     * @covers          \FOF40\Form\Field\Ordering::getRepeatable
     * @dataProvider    \FOF40\Tests\Form\Field\OrderingDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Ordering::getRepeatable %s - Case: '.$check['case'];

        $config = array(
            'tableName' => '#__foftest_foobars',
            'idFieldName' => 'foftest_foobar_id'
        );

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');

        $fakeView = new ClosureHelper(array(
            'getLists' => function() use($test){
                return (object)array('order' => $test['mock']['order']);
            },
            'hasAjaxOrderingSupport' => function() use($test){
                return $test['mock']['ajax'];
            },
            'getPagination' => function() use($test){
                $pagination = new ClosureHelper(array(
                    'orderUpIcon' => function(){
                        return '__ORDER_UP__';
                    },
                    'orderDownIcon' => function(){
                        return '__ORDER_DOWN__';
                    }
                ));

                $pagination->total = 5;

                return $pagination;
            },
            'getPerms' => function() use($test){
                return (object)array('editstate' => $test['mock']['perms']);
            }
        ));

        // I can't use the setter since there is a strong data type check (DataViewInterface)
        ReflectionHelper::setValue($form, 'view', $fakeView);

        $field = new Ordering();
        $field->setForm($form);
        $field->item = $model;

        foreach($test['properties'] as $key => $value)
        {
            ReflectionHelper::setValue($field, $key, $value);
        }

        $data = '<field type="Ordering" ';

        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';
        $xml   = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $result = $field->getRepeatable();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           OrderingField
     * @group           OrderingGetRepeatable
     * @covers          \FOF40\Form\Field\Ordering::getRepeatable
     */
    public function testGetRepeatableException()
    {
        $this->setExpectedException('FOF40\Form\Exception\DataModelRequired');

        $field = new Ordering();
        $field->getRepeatable();
    }
}
