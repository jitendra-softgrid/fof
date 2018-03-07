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
use JHtml;
use SimpleXMLElement;

defined('_JEXEC') or die;

/**
 * Form Field class for the FOF framework
 * A single checkbox
 */
class Checkbox extends FormField implements FieldInterface
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $type = 'Checkbox';

	/**
	 * The checked state of checkbox field.
	 *
	 * @var    boolean
	 * @since  4.0
	 */
	protected $checked = false;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   4.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'checked':
				return $this->checked;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   4.0
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'checked':
				$value = (string) $value;
				$this->checked = ($value == 'true' || $value == $name || $value == '1');
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   4.0
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		// Handle the default attribute
		$default = (string) $element['default'];

		if ($default)
		{
			$test = $this->form->getValue((string) $element['name'], $group);

			$value = ($test == $default) ? $default : null;
		}

		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$checked = (string) $this->element['checked'];
			$this->checked = ($checked == 'true' || $checked == 'checked' || $checked == '1');

			empty($this->value) || $this->checked ? null : $this->checked = true;
		}

		return $return;
	}

	/**
	 * Method to get the field input markup.
	 * The checked element sets the field to selected.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   4.0
	 */
	public function getInput()
	{
		// Initialize some field attributes.
		$class     = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$value     = !empty($this->default) ? $this->default : '1';
		$required  = $this->required ? ' required aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$checked   = $this->checked || !empty($this->value) ? ' checked' : '';

		// Initialize JavaScript field attributes.
		$onclick  = !empty($this->onclick) ? ' onclick="' . $this->onclick . '"' : '';
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

		return '<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '" value="'
			. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . $disabled . $onclick . $onchange
			. $required . $autofocus . ' />';
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
	 *
	 * @since   2.0
	 */
	public function getFieldContents(array $fieldOptions = array())
	{
		$id    = isset($fieldOptions['id']) ? 'id="' . $fieldOptions['id'] . '" ' : '';
		$class = $this->class . (isset($fieldOptions['class']) ? ' ' . $fieldOptions['class'] : '');

		$value     = $this->element['value'] ? (string) $this->element['value'] : '1';
		$disabled  = $this->disabled  ? ' disabled="disabled"' : '';
		$required  = $this->required  ? ' required="required" aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$checked   = $this->checked   || !empty($this->value) ? ' checked' : '';

		$onchange  = $this->onchange ? ' onchange="' . $this->onchange . '"' : '';
		$onclick   = $this->onclick  ? ' onclick="'  . $this->onclick . '"' : '';

		return '<span ' . ($id ? $id : '') . 'class="' . $class . '">' .
			'<input type="checkbox" name="' . $this->name . '" ' . ($id ? $id : '') . 'class="' . $this->id . ' ' . $class . '"' . ' value="'
			. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $disabled . $onclick . $onchange
			. $required . $autofocus . ' />' .
			'</span>';
	}
}
