<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form;

use Joomla\Registry\Registry;
use SimpleXMLElement;
use UnexpectedValueException;

defined('_JEXEC') or die;

// Detect if we have full UTF-8 and unicode PCRE support.
if (!defined('FOF4_COMPAT_UNICODE_PROPERTIES'))
{
	define('FOF4_COMPAT_UNICODE_PROPERTIES', (bool) @preg_match('/\pL/u', 'a'));
}

/**
 * Form Rule class for FOF
 *
 * @since  4.0
 */
class FormRule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $regex;

	/**
	 * The regular expression modifiers to use when testing a form field value.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $modifiers;

	/**
	 * Method to test the value.
	 *
	 * @param   SimpleXMLElement   $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form               $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   4.0
	 * @throws  UnexpectedValueException if rule is invalid.
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		// Check for a valid regex.
		if (empty($this->regex))
		{
			throw new UnexpectedValueException(sprintf('%s has invalid regex.', get_class($this)));
		}

		// Add unicode property support if available.
		if (FOF4_COMPAT_UNICODE_PROPERTIES)
		{
			$this->modifiers = (strpos($this->modifiers, 'u') !== false) ? $this->modifiers : $this->modifiers . 'u';
		}

		// Test the value against the regular expression.
		if (preg_match(chr(1) . $this->regex . chr(1) . $this->modifiers, $value))
		{
			return true;
		}

		return false;
	}
}
