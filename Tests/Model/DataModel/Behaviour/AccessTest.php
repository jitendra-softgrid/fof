<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\DataModel;

use FOF40\Model\DataModel\Behaviour\Access;
use FOF40\Tests\Helpers\ClosureHelper;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\TestContainer;

require_once 'AccessDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\Access::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\Access::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\Access
 */
class AccessTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           AccessOnAfterBuildQuery
     * @covers          FOF40\Model\DataModel\Behaviour\Access::onAfterBuildQuery
     * @dataProvider    AccessDataprovider::getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($test, $check)
    {
        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $model = $this->getMockBuilder('\\FOF40\\Tests\\Stubs\\Model\\DataModelStub')
            ->setMethods(array('applyAccessFiltering'))
            ->setConstructorArgs(array(static::$container, $config))
            ->getMock();

        $model->expects($check['access'] ? $this->once() : $this->never())->method('applyAccessFiltering');

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Access($dispatcher);

        $filter->onAfterBuildQuery($model, $query);
    }

    /**
     * @group           Behaviour
     * @group           AccessOnAfterLoad
     * @covers          FOF40\Model\DataModel\Behaviour\Access::onAfterLoad
     * @dataProvider    AccessDataprovider::getTestOnAfterLoad
     */
    public function testOnAfterLoad($test, $check)
    {
        $container = new TestContainer();
        $platform  = $container->platform;
        $platform::$user = new ClosureHelper(array(
            'getAuthorisedViewLevels' => function() use ($test){
                return $test['mock']['userAccess'];
            }
        ));

        $config = array(
            'idFieldName' => $test['tableid'],
            'tableName'   => $test['table']
        );

        $model = $this->getMockBuilder('FOF40\Tests\Stubs\Model\DataModelStub')
            ->setMethods(array('reset', 'getFieldValue'))
            ->setConstructorArgs(array($container, $config))
            ->getMock();
        $model->expects($check['reset'] ? $this->once() : $this->never())->method('reset');
        $model->method('getFieldValue')->willReturn($test['mock']['access']);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Access($dispatcher);

        $filter->onAfterLoad($model, $query);
    }
}
