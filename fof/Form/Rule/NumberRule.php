<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Rule;

defined('_JEXEC') or die;

use FOF40\Form\Form;
use FOF40\Form\FormRule;
use Joomla\Registry\Registry;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @since  4.0
 */
class NumberRule extends FormRule
{
	/**
	 * Method to test the range for a number value using min and max attributes.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form               $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   4.0
	 */
	public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		// Check if the field is required.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');

		// If the value is empty and the field is not required return True.
		if (($value === '' || $value === null) && ! $required)
		{
			return true;
		}

		$float_value = (float) $value;

		if (isset($element['min']))
		{
			$min = (float) $element['min'];

			if ($min > $float_value)
			{
				return false;
			}
		}

		if (isset($element['max']))
		{
			$max = (float) $element['max'];

			if ($max < $float_value)
			{
				return false;
			}
		}

		return true;
	}
}
