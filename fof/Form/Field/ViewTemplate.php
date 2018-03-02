<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Field;

use FOF40\Form\FieldInterface;
use FOF40\Form\Form;
use FOF40\Form\FormField;
use FOF40\Model\DataModel;
use FOF40\Container\Container;
use FOF40\View\View;
use \JText;

defined('_JEXEC') or die;

/**
 * Form Field class for the FOF framework
 * Displays a view template loaded from an outside source
 */
class ViewTemplate extends FormField implements FieldInterface
{
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 *
	 * @throws \Exception
	 */
	public function getStatic()
	{
		return $this->getRenderedTemplate();
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 *
	 * @throws \Exception
	 */
	public function getRepeatable()
	{
		return $this->getRenderedTemplate(true);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.0
	 *
	 * @throws \Exception
	 */
	public function getInput()
	{
		return $this->getRenderedTemplate();
	}

	/**
	 * Returns the rendered view template
	 *
	 * @param   bool $isRepeatable Is this a repeatable field?
	 *
	 * @return  string
	 *
	 * @throws \Exception
	 */
	protected function getRenderedTemplate($isRepeatable = false)
	{
		$sourceTemplate = isset($this->element['source']) ? (string) $this->element['source'] : null;
		$sourceView = isset($this->element['source_view']) ? (string) $this->element['source_view'] : null;
		$sourceViewType = isset($this->element['source_view_type']) ? (string) $this->element['source_view_type'] : 'html';
		$sourceComponent = isset($this->element['source_component']) ? (string) $this->element['source_component'] : null;

		if (empty($sourceTemplate))
		{
			return '';
		}

		$sourceContainer = empty($sourceComponent) ? $this->form->getContainer() : Container::getInstance($sourceComponent);

		if (empty($sourceView))
		{
			$viewObject = new View($sourceContainer, array(
				'name' => 'FAKE_FORM_VIEW'
			));
		}
		else
		{
			$viewObject = $sourceContainer->factory->view($sourceView, $sourceViewType);
		}

		$viewObject->populateFromModel($this->form->getModel());

		return $viewObject->loadAnyTemplate($sourceTemplate, array(
			'model'        => $isRepeatable ? $this->item : $this->form->getModel(),
			'rowid'        => $isRepeatable ? $this->rowid : null,
			'form'         => $this->form,
			'formType'     => $this->form->getAttribute('type', 'edit'),
			'fieldValue'   => $this->value,
			'fieldElement' => $this->element,
		));
	}
}
