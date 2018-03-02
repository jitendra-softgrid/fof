<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\Actions;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once __DIR__ . '/ActionsDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Actions::<private>
 * @covers  FOF40\Form\Field\Actions::<protected>
 */
class ActionsTest extends FOFTestCase
{
    /**
     * @group           AccessLevel
     * @group           Actions__get
     * @covers          FOF40\Form\Field\Actions::__get
     * @dataProvider    ActionsDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Actions')
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
     * @group           AccessLevel
     * @group           ActionsGetStatic
     * @covers          FOF40\Form\Field\Actions::getStatic
     */
    public function testGetStatic()
    {
        $this->setExpectedException('FOF40\Form\Exception\GetStaticNotAllowed');

        $field = new Actions();

        $field->getStatic();
    }

    /**
     * @group           AccessLevel
     * @group           ActionsGetRepeatable
     * @covers          FOF40\Form\Field\Actions::getRepeatable
     * @dataProvider    ActionsDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $msg = 'Actions::getRepeatable %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['id'],
            'tableName'   => $test['table']
        );

        $model = new DataModelStub(static::$container, $config);
        $model->setFieldValue('enabled', $test['enabled']);

        $fakeField = new ClosureHelper(array(
            'getRepeatable' => function(){
                return '__FAKE_PUBLISH__';
            }
        ));

        $field = $this->getMockBuilder('FOF40\Form\Field\Actions')
            ->setMethods(array('getPublishedField'))
            ->getMock();

        $field->expects($this->exactly($check['publishField']))->method('getPublishedField')->willReturn($fakeField);

        $data  = '<field type="Actions"';
        $data .= ' show_published="'.($test['published'] ? 1 : 0).'"';
        $data .= ' show_unpublished="'.($test['unpublished'] ? 1 : 0).'"';
        $data .= ' show_archived="'.($test['archived'] ? 1 : 0).'"';
        $data .= ' show_trash="'.($test['trash'] ? 1 : 0).'"';
        $data .= ' show_all="'.($test['all'] ? 1 : 0).'"';

        $data .= ' />';

        $xml  = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>'.$data);

        ReflectionHelper::setValue($field, 'element', $xml);
        ReflectionHelper::setValue($field, 'item', $model);

        $html = $field->getRepeatable();

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           AccessLevel
     * @group           ActionsGetRepeatable
     * @covers          FOF40\Form\Field\Actions::getRepeatable
     */
    public function testGetRepeatableException()
    {
        $this->setExpectedException('FOF40\Form\Exception\DataModelRequired');

        $field = new Actions();

        $field->getRepeatable();
    }
}