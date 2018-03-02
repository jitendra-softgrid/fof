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
 * Joomla! access levels
 */
class AccessLevel extends BaseList implements FieldInterface
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   4.0
	 */
	public function getInput()
	{
		$attr = '';

		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= $this->disabled ? ' disabled' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		// Get the field options.
		$options = $this->getOptions();

		return JHtml::_('access.level', $this->name, $this->value, $attr, $options, $this->id);
	}

	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getStatic()
	{
		if (isset($this->element['legacy']))
		{
			return $this->getInput();
		}

		$options = array(
			'id' => $this->id
		);

		return $this->getFieldContents($options);
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getRepeatable()
	{
		if (isset($this->element['legacy']))
		{
			return $this->getInput();
		}

		$options = array(
			'class' => $this->id
		);

		return $this->getFieldContents($options);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @param   array   $fieldOptions  Options to be passed into the field
	 *
	 * @return  string  The field HTML
	 */
	public function getFieldContents(array $fieldOptions = array())
	{
		/** @var array|null The select options coming from the access levels of the site */
		static $defaultOptions = null;

		$id    = isset($fieldOptions['id']) ? 'id="' . $fieldOptions['id'] . '" ' : '';
		$class = $this->class . (isset($fieldOptions['class']) ? ' ' . $fieldOptions['class'] : '');

		$params = $this->getOptions();

		if (is_null($defaultOptions))
		{
			$db    = $this->form->getContainer()->platform->getDbo();
			$query = $db->getQuery(true)
			            ->select('a.id AS value, a.title AS text')
			            ->from('#__viewlevels AS a')
			            ->group('a.id, a.title, a.ordering')
			            ->order('a.ordering ASC')
			            ->order($db->qn('title') . ' ASC');

			// Get the options.
			$defaultOptions = $db->setQuery($query)->loadObjectList();
		}

        $options = $defaultOptions;

		// If params is an array, push these options to the array
		if (is_array($params))
		{
			$options = array_merge($params, $defaultOptions);
		}

		// If all levels is allowed, push it into the array.
		elseif ($params)
		{
			array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_ACCESS_SHOW_ALL_LEVELS')));
		}

		return '<span ' . ($id ? $id : '') . 'class="'. $class . '">' .
			htmlspecialchars(GenericList::getOptionName($options, $this->value), ENT_COMPAT, 'UTF-8') .
			'</span>';
	}
}
