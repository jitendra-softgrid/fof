<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\DataModel\Relation\Relation\BelongsTo;

use FOF40\Model\DataModel\Relation\BelongsTo;
use FOF40\Tests\Helpers\DatabaseTest;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once 'BelongsToDataprovider.php';

/**
 * @covers      FOF40\Model\DataModel\Relation\BelongsTo::<protected>
 * @covers      FOF40\Model\DataModel\Relation\BelongsTo::<private>
 * @package     FOF40\Tests\DataModel\Relation\BelongsTo
 */
class BelongsToTest extends DatabaseTest
{
    /**
     * @group           BelongsTo
     * @group           BelongsToConstruct
     * @covers          FOF40\Model\DataModel\Relation\BelongsTo::__construct
     * @dataProvider    BelongsToDataprovider::getTestConstruct
     */
    public function testConstruct($test, $check)
    {
        $msg = 'BelongsTo::__construct %s - Case: '.$check['case'];

        $model    = $this->buildModel();
        $relation = new BelongsTo($model, 'Parents', $test['local'], $test['foreign']);

        $this->assertEquals($check['local'], ReflectionHelper::getValue($relation, 'localKey'), sprintf($msg, 'Failed to set the local key'));
        $this->assertEquals($check['foreign'], ReflectionHelper::getValue($relation, 'foreignKey'), sprintf($msg, 'Failed to set the foreign key'));
    }

    /**
     * @group           BelongsTo
     * @group           BelongsToGetNew
     * @covers          FOF40\Model\DataModel\Relation\BelongsTo::getNew
     */
    public function testGetNew()
    {
        $model = $this->buildModel();
        $relation = new BelongsTo($model, 'Parents');

        $this->setExpectedException('FOF40\Model\DataModel\Relation\Exception\NewNotSupported');

        $relation->getNew();
    }

    /**
     * @param   string    $class
     *
     * @return \FOF40\Model\DataModel
     */
    protected function buildModel($class = null)
    {
        if(!$class)
        {
            $class = '\\FOF40\\Tests\\Stubs\\Model\\DataModelStub';
        }

        $config = array(
            'idFieldName' => 'fakeapp_children_id',
            'tableName'   => '#__fakeapp_children'
        );

        return new $class(static::$container, $config);
    }
}
