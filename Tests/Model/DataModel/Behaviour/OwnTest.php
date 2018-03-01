<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\Own;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Stubs\Model\DataModelStub;

require_once 'OwnDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Own::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Own::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Own
 */
class OwnTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           OwnOnAfterBuildQuery
     * @covers          FOF40\Model\DataModel\Behaviour\Own::onAfterBuildQuery
     * @dataProvider    OwnDataprovider::getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($test, $check)
    {
        $msg = 'Own::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $platform = static::$container->platform;
        $platform::$user = (object)array('id' => 99);

        $model = new DataModelStub(static::$container, $config);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Own($dispatcher);

        $filter->onAfterBuildQuery($model, $query);

        $rawQuery = (string) $query;

        if($check['contains'])
        {
            $this->assertNotFalse(stripos($rawQuery, $check['query']), sprintf($msg, 'Query should contain the query clause'));
        }
        else
        {
            $this->assertFalse(stripos($rawQuery, $check['query']), sprintf($msg, 'Query should not contain the query clause'));
        }
    }

    /**
     * @group           Behaviour
     * @group           OwnOnAfterLoad
     * @covers          FOF40\Model\DataModel\Behaviour\Own::onAfterLoad
     * @dataProvider    OwnDataprovider::getTestOnAfterLoad
     */
    public function testOnAfterLoad($test, $check)
    {
        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $platform = static::$container->platform;
        $platform::$user = (object)array('id' => 99);

        $model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
            ->setMethods(array('reset', 'getFieldValue'))
            ->setConstructorArgs(array(static::$container, $config))
            ->getMock();
        $model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');
        $model->method('getFieldValue')->willReturn($test['mock']['created_by']);

        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Own($dispatcher);

        $keys = array();
        $filter->onAfterLoad($model, $keys);
    }
}
