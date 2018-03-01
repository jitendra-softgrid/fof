<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Utils;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Utils\StringHelper;

/**
 * @covers  FOF40\Utils\StringHelper::<protected>
 * @covers  FOF40\Utils\StringHelper::<private>
 */
class StringTest extends FOFTestCase
{
	/**
	 * @covers       FOF40\Utils\StringHelper::toBool
	 *
	 * @dataProvider FOF40\Tests\Utils\StringProvider::getTestToBool
	 *
	 * @param string $value
	 * @param bool   $expected
	 * @param string $message
	 */
	public function testToBool($value, $expected, $message)
	{
		$actual = StringHelper::toBool($value);

		$this->assertEquals($expected, $actual, $message);
	}
}
