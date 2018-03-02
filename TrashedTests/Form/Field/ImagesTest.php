<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Form\Field;

use FOF40\Form\Field\Images;
use FOF40\Form\Form;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\ReflectionHelper;
use FOF40\Tests\Helpers\TestJoomlaPlatform;
use org\bovigo\vfs\vfsStream;

require_once __DIR__ . '/ImagesDataprovider.php';

/**
 * @covers  FOF40\Form\Field\Images::<private>
 * @covers  FOF40\Form\Field\Images::<protected>
 */
class ImagesTest extends FOFTestCase
{
    /**
     * @group           Images
     * @group           ImagesGetFieldContents
     * @covers          FOF40\Form\Field\Images::getFieldContents
     * @dataProvider    ImagesDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Images::getFieldContents %s - Case: '.$check['case'];

        // Let's mock the filesystem, so I can create and remove files at will
        vfsStream::setup('root', null, $test['filesystem']);

        /** @var TestJoomlaPlatform $platform */
        $platform = static::$container->platform;
        $platform::$uriRoot = 'http://www.example.com';
        $platform::$baseDirs = array(
            'root' => vfsStream::url('root')
        );

        $form  = new Form(static::$container, 'Foobar');
        $field = new Images();
        $field->setForm($form);

        $data = '<field type="Images" name="foobar" ';

        foreach($test['attributes'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';
        $xml   = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->setValue($test['value']);

        $result = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}