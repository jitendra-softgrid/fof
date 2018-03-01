<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Utils;

use FOF40\Tests\Helpers\FOFTestCase;
use FOF40\Tests\Stubs\Utils\IpStub;

require_once 'IpDataprovider.php';

/**
 * @covers  \FOF40\Utils\Ip::<protected>
 * @covers  \FOF40\Utils\Ip::<private>
 */
class IpTest extends FOFTestCase
{
	/**
	 * @group			Ip
	 * @dataProvider    IpDataprovider::getDetectAndCleanIP
	 */
	public function testDetectAndCleanIP($test, $check)
	{
		$msg = 'Ip::detectIP %s - Case: '.$check['case'];

		$ip = new IpStub();

		$ip::$fakeIP = $test['fakeIP'];
		$ip::setUseFirstIpInChain($test['useFirst']);

		$result = $ip::detectAndCleanIP();

		$this->assertEquals($check['result'], $result, $msg);
	}
}
