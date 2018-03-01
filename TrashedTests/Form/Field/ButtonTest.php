<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF30\Tests\Form\Field;

use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once 'ButtonDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Button::<private>
 * @covers  FOF30\Form\Field\Button::<protected>
 */
class ButtonTest extends FOFTestCase
{
    /**
     * @group           Button
     * @group           ButtonGetStatic
     * @covers          FOF30\Form\Field\Button::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMockBuilder('FOF30\Form\Field\Button')
            ->setMethods(array('getInput'))
            ->getMock();

        $field->expects($this->once())->method('getInput');

        $field->getStatic();
    }

    /**
     * @group           Button
     * @group           ButtonGetRepeatable
     * @covers          FOF30\Form\Field\Button::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMockBuilder('FOF30\Form\Field\Button')
            ->setMethods(array('getInput'))
            ->getMock();

        $field->expects($this->once())->method('getInput');

        $field->getRepeatable();
    }

    /**
     * @group           Button
     * @group           ButtonGetInput
     * @covers          FOF30\Form\Field\Button::getInput
     * @dataProvider    ButtonDataprovider::getTestGetInput
     */
    public function testGetInput($test, $check)
    {
        $msg = 'Button::getInput %s - Case: '.$check['case'];

        $field = $this->getMockBuilder('FOF30\Form\Field\Button')
            ->setMethods(array('parseFieldTags'))
            ->getMock();

        $field->method('parseFieldTags')->willReturn('__FAKE_URL__');

        $xml = simplexml_load_string($test['xml']);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->id      = $test['id'];
        $field->class   = $test['class'];
        $field->onclick = $test['onclick'];
        $field->value   = $test['value'];

        $html = $field->getInput();

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
