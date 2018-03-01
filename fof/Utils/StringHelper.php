<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Utils;

defined('_JEXEC') or die;

abstract class StringHelper
{
	/**
	 * Convert a string to a boolean.
	 *
	 * @param   string  $string  The string.
	 *
	 * @return  boolean  The converted string
	 */
	public static function toBool($string)
	{
		$string = trim((string)$string);
		$string = strtolower($string);

		if (in_array($string, array(1, 'true', 'yes', 'on', 'enabled'), true))
		{
			return true;
		}

		if (in_array($string, array(0, 'false', 'no', 'off', 'disabled'), true))
		{
			return false;
		}

		return (bool)$string;
	}
}
