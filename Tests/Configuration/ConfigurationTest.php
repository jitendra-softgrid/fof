<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Configuration\Domain;

use FOF40\Tests\Helpers\FOFTestCase;

/**
 * @covers  FOF40\Configuration\Configuration::<protected>
 * @covers  FOF40\Configuration\Configuration::<private>
 */
class ConfigurationTest extends FOFTestCase
{
	/** @var   array  The data returned from parsing the XML file, used to test fetching data */
	protected $data = array();

	/**
	 * @return  void
	 */
	protected function setUp()
	{
		self::$container->backEndPath = realpath(__DIR__ . '/../_data/configuration');
	}

	/**
	 * @covers  FOF40\Configuration\Configuration::__construct
	 * @covers  FOF40\Configuration\Configuration::parseComponent
	 * @covers  FOF40\Configuration\Configuration::parseComponentArea
	 * @covers  FOF40\Configuration\Configuration::getDomains
	 * @covers  FOF40\Configuration\Configuration::get
	 *
	 * @return  void
	 */
	public function testConstructor()
	{
		$x = self::$container->appConfig;

		$this->assertInstanceOf('\\FOF40\\Configuration\\Configuration', $x, 'Configuration object must be of correct type');

		$actual = $x->get('models.Orders.field.enabled', null);

		$this->assertEquals('published', $actual, 'get() must return valid domain data');
	}
}
