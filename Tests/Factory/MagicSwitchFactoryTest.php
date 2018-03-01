<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Factory;

use FOF40\Factory\MagicSwitchFactory;
use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Helpers\TestContainer;

require_once 'MagicSwitchFactoryDataprovider.php';

/**
 * @covers      FOF40\Factory\MagicSwitchFactory::<protected>
 * @covers      FOF40\Factory\MagicSwitchFactory::<private>
 * @package     FOF40\Tests\MagicSwitchFactory
 */
class MagicSwitchFactoryTest extends FOFTestCase
{
    /**
     * @group           MagicSwitchFactory
     * @covers          FOF40\Factory\MagicSwitchFactory::controller
     * @dataProvider    MagicSwitchFactoryDataprovider::getTestController
     */
    public function testController($test, $check)
    {
        $msg   = 'MagicSwitchFactory::controller %s - Case: '.$check['case'];

        $factory = new MagicSwitchFactory(static::$container);

        $result = $factory->controller($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicSwitchFactory
     * @covers          FOF40\Factory\MagicSwitchFactory::model
     * @dataProvider    MagicSwitchFactoryDataprovider::getTestModel
     */
    public function testModel($test, $check)
    {
        $msg   = 'MagicSwitchFactory::model %s - Case: '.$check['case'];

        $factory = new MagicSwitchFactory(static::$container);

        $result = $factory->model($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicSwitchFactory
     * @covers          FOF40\Factory\MagicSwitchFactory::view
     * @dataProvider    MagicSwitchFactoryDataprovider::getTestView
     */
    public function testView($test, $check)
    {
        $msg   = 'MagicSwitchFactory::view %s - Case: '.$check['case'];

        $platform = static::$container->platform;
        $platform::$template = 'fake_test_template';
        $platform::$uriBase  = 'www.example.com';

        $factory = new MagicSwitchFactory(static::$container);

        $result = $factory->view($test['view']);

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicSwitchFactory
     * @covers          FOF40\Factory\MagicSwitchFactory::dispatcher
     * @dataProvider    MagicSwitchFactoryDataprovider::getTestDispatcher
     */
    public function testDispatcher($test, $check)
    {
        $msg   = 'MagicSwitchFactory::dispatcher %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => $test['component'],
            'backEndPath' => $test['backend_path']
        ));

        $platform = $container->platform;
        $platform::$isAdmin = $test['backend'];

        // Required so we force FOF to read the fof.xml file
        $dummy = $container->appConfig;

        $factory = new MagicSwitchFactory($container);

        $result = $factory->dispatcher();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }

    /**
     * @group           MagicSwitchFactory
     * @covers          FOF40\Factory\MagicSwitchFactory::transparentAuthentication
     * @dataProvider    MagicSwitchFactoryDataprovider::getTestTransparentAuthentication
     */
    public function testTransparentAuthentication($test, $check)
    {
        $msg   = 'MagicSwitchFactory::transparentAuthentication %s - Case: '.$check['case'];

        $container = new TestContainer(array(
            'componentName' => $test['component'],
            'backEndPath' => $test['backend_path']
        ));

        $platform = $container->platform;
        $platform::$isAdmin = $test['backend'];

        // Required so we force FOF to read the fof.xml file
        $dummy = $container->appConfig;

        $factory = new MagicSwitchFactory($container);

        $result = $factory->transparentAuthentication();

        $this->assertEquals($check['result'], get_class($result), sprintf($msg, 'Returned the wrong result'));
    }
}
