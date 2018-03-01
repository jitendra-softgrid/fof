<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Rule;

defined('_JEXEC') or die;

use FOF40\Form\FormRule;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @since  4.0
 */
class BooleanRule extends FormRule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $regex = '^(?:[01]|true|false)$';

	/**
	 * The regular expression modifiers to use when testing a form field value.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $modifiers = 'i';
}
