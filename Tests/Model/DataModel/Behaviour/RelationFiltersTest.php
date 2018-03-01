<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\DataModel;

use Fakeapp\Site\Model\Parents;
use FOF40\Model\DataModel\Behaviour\RelationFilters;
use FOF40\Tests\Helpers\DatabaseTest;

require_once 'RelationFiltersDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Behaviour\RelationFilters::<protected>
 * @covers      FOF40\Model\DataModel\Behaviour\RelationFilters::<private>
 * @package     FOF40\Tests\DataModel\Behaviour\RelationFilters
 */
class RelationFiltersTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           RelationFiltersOnAfterBuildQuery
     * @covers          FOF40\Model\DataModel\Behaviour\RelationFilters::onAfterBuildQuery
     * @dataProvider    RelationFiltersDataprovider::getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($test, $check)
    {
        \PHPUnit_Framework_Error_Warning::$enabled = false;

        $msg = 'RelationFilters::onAfterBuildQuery %s - Case: '.$check['case'];

        $config = array(
            'relations'   => array(
                array(
                    'itemName' => 'children',
                    'type' => 'hasMany',
                    'foreignModelClass' => 'Children',
                    'localKey' => 'fakeapp_parent_id',
                    'foreignKey' => 'fakeapp_parent_id'
                )
            )
        );

        /** @var \FOF40\Model\DataModel $model */
        $model = new Parents(static::$container, $config);

        $query      = \JFactory::getDbo()->getQuery(true)->select('*')->from('test');
        $dispatcher = $model->getBehavioursDispatcher();
        $filter     = new RelationFilters($dispatcher);

        // I have to setup a filter
        $model->has('children', $test['operator'], $test['value']);

        $filter->onAfterBuildQuery($model, $query);

        $this->assertEquals($check['query'], trim((string) $query), sprintf($msg, 'Failed to build the search query'));
    }
}

