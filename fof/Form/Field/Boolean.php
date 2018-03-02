<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Field;

use FOF40\Form\FieldInterface;
use FOF40\Form\Form;
use FOF40\Model\DataModel;
use \JHtml;
use \JText;

defined('_JEXEC') or die;

/**
 * Form Field class for FOF
 * Supports a drop-down list of Yes/No (boolean) answers.
 */
class Boolean extends GenericList implements FieldInterface
{

	/**
	 * Method to get the field options.
	 *
	 * Ordering is disabled by default. You can enable ordering by setting the
	 * 'order' element in your form field. The other order values are optional.
	 *
	 * - order					What to order.			Possible values: 'name' or 'value' (default = false)
	 * - order_dir				Order direction.		Possible values: 'asc' = Ascending or 'desc' = Descending (default = 'asc')
	 * - order_case_sensitive	Order case sensitive.	Possible values: 'true' or 'false' (default = false)
	 *
	 * @return  array  The field option objects.
	 *
	 * @since	2.1.0b2
	 */
	protected function getOptions()
	{
		$options = parent::getOptions();

		$defaultOptions = array(
			JHtml::_('select.option', 1, \JText::_('JYES')),
			JHtml::_('select.option', 0, \JText::_('JNO')),
		);

		$options = array_merge($defaultOptions, $options);

		return $options;
	}

}
