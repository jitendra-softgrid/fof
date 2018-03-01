<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;

require_once __DIR__ . '/CaptchaDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Captcha::<private>
 * @covers  FOF40\Form\Field\Captcha::<protected>
 */
class CaptchaTest extends FOFTestCase
{
    /**
     * @group           Captcha
     * @group           Captcha__get
     * @covers          FOF40\Form\Field\Captcha::__get
     * @dataProvider    CaptchaDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Captcha')->setMethods(array('getStatic', 'getRepeatable'))->getMock();

        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Captcha
     * @group           CaptchaGetStatic
     * @covers          FOF40\Form\Field\Captcha::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Captcha')->setMethods(array('getInput'))->getMock();

        $field->expects($this->once())->method('getInput');

        $field->getStatic();
    }

    /**
     * @group           Captcha
     * @group           CaptchaGetRepeatable
     * @covers          FOF40\Form\Field\Captcha::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMockBuilder('FOF40\Form\Field\Captcha')->setMethods(array('getInput'))->getMock();
        $field->expects($this->once())->method('getInput');

        $field->getRepeatable();
    }
}
