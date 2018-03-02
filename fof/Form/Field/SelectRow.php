<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Field;

use FOF40\Form\Exception\DataModelRequired;
use FOF40\Form\Exception\GetInputNotAllowed;
use FOF40\Form\Exception\GetStaticNotAllowed;
use FOF40\Form\FieldInterface;
use FOF40\Form\Form;
use FOF40\Form\FormField;
use FOF40\Model\DataModel;
use JHtml;

defined('_JEXEC') or die;

/**
 * Form Field class for FOF
 * Renders the checkbox in browse views which allows you to select rows
 */
class SelectRow extends FormField implements FieldInterface
{
	/**
	 * Method to get the field input markup for this field type.
	 *
	 * @since 2.0
	 *
	 * @return  string  The field input markup.
	 *
	 * @throws  GetInputNotAllowed
	 */
	public function getInput()
	{
		throw new GetInputNotAllowed(get_called_class());
	}

	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 *
	 * @throws  \LogicException
	 */
	public function getStatic()
	{
		throw new GetStaticNotAllowed(get_called_class());
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 *
	 * @throws  DataModelRequired
	 */
	public function getRepeatable()
	{
		// Should I support checked-out elements?
		$checkoutSupport = false;

		if (isset($this->element['checkout']))
		{
			$checkoutSupportValue = (string)$this->element['checkout'];
			$checkoutSupport = in_array(strtolower($checkoutSupportValue), array('yes', 'true', 'on', 1));
		}

		if (!($this->item instanceof DataModel))
		{
			throw new DataModelRequired(get_called_class());
		}

		// Is this record checked out?
		$userId = $this->form->getContainer()->platform->getUser()->get('id', 0);
		$checked_out = false;

		if ($checkoutSupport)
		{
			$checked_out     = $this->item->isLocked($userId);
		}

		// Get the key id for this record
		$key_field = $this->item->getKeyName();
		$key_id    = $this->item->$key_field;

		// Get the HTML
		return JHtml::_('grid.id', $this->rowid, $key_id, $checked_out);
	}
}
