<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Tests\Stubs\Utils;

use FOF40\Utils\Ip;

class IpStub extends Ip
{
	public static $fakeIP = null;

    protected static function detectIP()
	{
		if (!is_null(static::$fakeIP))
		{
			return static::$fakeIP;
		}

		return parent::detectIP();
	}

	public static function detectAndCleanIP()
	{
		return parent::detectAndCleanIP();
	}
}
