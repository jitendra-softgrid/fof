<?php
/**
 * @package     FOF
 * @copyright Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\DataModel;

use FOF30\Model\DataModel\Behaviour\Filters;
use FOF30\Tests\Helpers\DatabaseTest;

require_once 'FiltersDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\Filters::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\Filters::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\Filters
 */
class FiltersTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           FiltersOnAfterBuildQuery
     * @covers          FOF30\Model\DataModel\Behaviour\Filters::onAfterBuildQuery
     * @dataProvider    FiltersDataprovider::getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($test, $check)
    {
        //\PHPUnit_Framework_Error_Warning::$enabled = false;

        $msg = 'Filters::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMockBuilder('\\FOF30\\Tests\\Stubs\\Model\\DataModelStub')
            ->setMethods(array('getState'))
            ->setConstructorArgs(array(static::$container, $config))
            ->getMock();

        $model->method('getState')->willReturnCallback(function($key, $default = null) use ($test){
            if(isset($test['mock']['state'][$key])){
                return $test['mock']['state'][$key];
            }

            return $default;
        });

        $model->setIgnoreRequest($test['ignore']);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new Filters($dispatcher);

        $filter->onAfterBuildQuery($model, $query);

        $this->assertEquals($check['query'], trim((string) $query), sprintf($msg, 'Failed to build the query'));
    }
}
