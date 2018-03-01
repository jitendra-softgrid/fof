<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Form;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/PluginsDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Plugins::<private>
 * @covers  FOF40\Form\Field\Plugins::<protected>
 */
class PluginsTest extends FOFTestCase
{
    /**
     * @group           Plugins
     * @group           Plugins__get
     * @covers          FOF40\Form\Field\Plugins::__get
     * @dataProvider    PluginsDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Plugins')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Plugins
     * @group           PluginsGetStatic
     * @covers          FOF40\Form\Field\Plugins::getStatic
     * @dataProvider    PluginsDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Plugins')->setMethods(array('getInput', 'getFieldContents'))->getMock();

        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Plugins" name="foobar" ';

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
     * @group           Plugins
     * @group           PluginsGetRepeatable
     * @covers          FOF40\Form\Field\Plugins::getRepeatable
     * @dataProvider    PluginsDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Plugins')->setMethods(array('getInput', 'getFieldContents'))->getMock();

        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Plugins" name="foobar" ';

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
     * @group           Plugins
     * @group           PluginsGetFieldContents
     * @covers          FOF40\Form\Field\Plugins::getFieldContents
     * @dataProvider    PluginsDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Plugins::getFieldContents %s - Case: '.$check['case'];

        $field = $this->getMockBuilder('FOF40\Form\Field\Plugins')->setMethods(array('getOptions'))->getMock();

        $field->method('getOptions')->willReturn($test['mock']['options']);

        // Registered access level
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
