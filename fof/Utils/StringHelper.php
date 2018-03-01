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
	 * Convert common northern European languages' letters into plain ASCII. This
	 * is a rudimentary transliteration.
	 *
	 * @param   string  $value  The value to convert to ASCII
	 *
	 * @return  string  The converted string
	 *
	 * @deprecated   3.0  Use JFactory::getLanguage()->transliterate instead
	 *
	 * @codeCoverageIgnore
	 */
	public static function toASCII($value)
	{
		if (class_exists('\JLog'))
		{
			\JLog::add('FOF40\\Utils\\StringHelper::toASCII is deprecated. Use JFactory::getLanguage()->transliterate instead', \JLog::WARNING, 'deprecated');
		}

		$lang = \JFactory::getLanguage();
		return $lang->transliterate($value);
	}

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
