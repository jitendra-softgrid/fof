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
use JComponentHelper;
use JHtml;
use Joomla\CMS\Factory;
use \JText;
use JUri;
use SimpleXMLElement;

defined('_JEXEC') or die;

/**
 * Form Field class for the FOF framework
 * Supports a one line text field.
 */
class Text extends FormField implements FieldInterface
{
	/**
	 * The allowable maxlength of the field.
	 *
	 * @var    integer
	 * @since  4.0
	 */
	protected $maxLength;

	/**
	 * The mode of input associated with the field.
	 *
	 * @var    mixed
	 * @since  4.0
	 */
	protected $inputmode;

	/**
	 * The name of the form field direction (ltr or rtl).
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $dirname;

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $layout = 'joomla.form.field.text';

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
			case 'maxLength':
			case 'dirname':
			case 'inputmode':
				return $this->$name;
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
			case 'maxLength':
				$this->maxLength = (int) $value;
				break;

			case 'dirname':
				$value = (string) $value;
				$this->dirname = ($value == $name || $value == 'true' || $value == '1');
				break;

			case 'inputmode':
				$this->inputmode = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a Form object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     FormField::setup()
	 * @since   4.0
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{
			$inputmode = (string) $this->element['inputmode'];
			$dirname = (string) $this->element['dirname'];

			$this->inputmode = '';
			$inputmode = preg_replace('/\s+/', ' ', trim($inputmode));
			$inputmode = explode(' ', $inputmode);

			if (!empty($inputmode))
			{
				$defaultInputmode = in_array('default', $inputmode) ? JText::_('JLIB_FORM_INPUTMODE') . ' ' : '';

				foreach (array_keys($inputmode, 'default') as $key)
				{
					unset($inputmode[$key]);
				}

				$this->inputmode = $defaultInputmode . implode(' ', $inputmode);
			}

			// Set the dirname.
			$dirname = ((string) $dirname == 'dirname' || $dirname == 'true' || $dirname == '1');
			$this->dirname = $dirname ? $this->getName($this->fieldname . '_dir') : false;

			$this->maxLength = (int) $this->element['maxlength'];
		}

		return $result;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   4.0
	 */
	public function getInput()
	{
		if ($this->element['useglobal'])
		{
			$component = Factory::getApplication()->input->getCmd('option');

			// Get correct component for menu items
			if ($component == 'com_menus')
			{
				$link      = $this->form->getData()->get('link');
				$uri       = new JUri($link);
				$component = $uri->getVar('option', 'com_menus');
			}

			$params = JComponentHelper::getParams($component);
			$value  = $params->get($this->fieldname);

			// Try with global configuration
			if (is_null($value))
			{
				$value = Factory::getConfig()->get($this->fieldname);
			}

			// Try with menu configuration
			if (is_null($value) && Factory::getApplication()->input->getCmd('option') == 'com_menus')
			{
				$value = JComponentHelper::getParams('com_menus')->get($this->fieldname);
			}

			if (!is_null($value))
			{
				$value = (string) $value;

				$this->hint = JText::sprintf('JGLOBAL_USE_GLOBAL_VALUE', $value);
			}
		}

		return $this->getRenderer($this->layout)->render($this->getLayoutData());
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
		if (is_array($this->value))
		{
			$this->value = empty($this->value) ? '' : print_r($this->value, true);
		}

		if (isset($this->element['legacy']))
		{
			return $this->getInput();
		}

		$class = $this->class ? ' class="' . $this->class . '"' : '';

		$empty_replacement = $this->element['empty_replacement'] ? (string) $this->element['empty_replacement'] : '';

		if (!empty($empty_replacement) && empty($this->value))
		{
			$this->value = JText::_($empty_replacement);
		}

		return '<span id="' . $this->id . '" ' . $class . '>' .
			htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .
			'</span>';
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
		if (is_array($this->value))
		{
			$this->value = print_r($this->value, true);
		}

		if (isset($this->element['legacy']))
		{
			return $this->getInput();
		}

		// Should I support checked-out elements?
		$checkoutSupport = false;

		if (isset($this->element['checkout']))
		{
			$checkoutSupportValue = (string)$this->element['checkout'];
			$checkoutSupport = in_array(strtolower($checkoutSupportValue), array('yes', 'true', 'on', 1));
		}

		// Initialise
		$class					= $this->class ? $this->class : $this->id;
		$format_string			= $this->element['format'] ? (string) $this->element['format'] : '';
		$format_if_not_empty	= in_array((string) $this->element['format_if_not_empty'], array('true', '1', 'on', 'yes'));
		$parse_value			= in_array((string) $this->element['parse_value'], array('true', '1', 'on', 'yes'));
		$link_url				= $this->element['url'] ? (string) $this->element['url'] : '';
		$empty_replacement		= $this->element['empty_replacement'] ? (string) $this->element['empty_replacement'] : '';
		$format_source_file     = empty($this->element['format_source_file']) ? '' : (string) $this->element['format_source_file'];
		$format_source_class    = empty($this->element['format_source_class']) ? '' : (string) $this->element['format_source_class'];
		$format_source_method   = empty($this->element['format_source_method']) ? '' : (string) $this->element['format_source_method'];

		if ($link_url && ($this->item instanceof DataModel))
		{
			$link_url = $this->parseFieldTags($link_url);
		}
		else
		{
			$link_url = false;
		}

		// Get the (optionally formatted) value
		$value = $this->value;

		if (!empty($empty_replacement) && empty($this->value))
		{
			$value = JText::_($empty_replacement);
		}

		if ($parse_value)
		{
			$value = $this->parseFieldTags($value);
		}

		if (!empty($format_string) && (!$format_if_not_empty || ($format_if_not_empty && !empty($this->value))))
		{
			$format_string = $this->parseFieldTags($format_string);
			$value = sprintf($format_string, $value);
		}
		elseif ($format_source_class && $format_source_method)
		{
			// Maybe we have to load a file?
			if (!empty($format_source_file))
			{
				$format_source_file = $this->form->getContainer()->template->parsePath($format_source_file, true);

				if ($this->form->getContainer()->filesystem->fileExists($format_source_file))
				{
					include_once $format_source_file;
				}
			}

			// Make sure the class and method exist
			if (class_exists($format_source_class, true) && in_array($format_source_method, get_class_methods($format_source_class)))
			{
				$value = $format_source_class::$format_source_method($value);
				$value = $this->parseFieldTags($value);
			}
			else
			{
				$value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
			}
		}
		else
		{
			$value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
		}

		// Create the HTML
		$html = '<span class="' . $class . '">';

		$userId = $this->form->getContainer()->platform->getUser()->id;

		if ($checkoutSupport && $this->item->isLocked($userId))
		{
			$key_field = $this->item->getKeyName();
			$key_id    = $this->item->$key_field;

			$lockedBy = '';
			$lockedOn = '';

			if ($this->item->hasField('locked_by'))
			{
				$lockedUser = $this->form->getContainer()->platform->getUser($this->item->getFieldValue('locked_by'));
				$lockedBy = $lockedUser->name . ' (' . $lockedUser->username . ')';
			}

			if ($this->item->hasField('locked_on'))
			{
				$lockedOn = $this->item->getFieldValue('locked_on');
			}

			$html .= \JHtml::_('jgrid.checkedout', $key_id, $lockedBy, $lockedOn, '', true);
		}

		if ($link_url)
		{
			$html .= '<a href="' . $link_url . '">';
		}

		$html .= $value;

		if ($link_url)
		{
			$html .= '</a>';
		}

		$html .= '</span>';

		return $html;
	}

	/**
	 * Replace string with tags that reference fields
	 *
	 * @param   string  $text  Text to process
	 *
	 * @return  string         Text with tags replace
	 */
    protected function parseFieldTags($text)
    {
        $ret = $text;

        // Replace [ITEM:ID] in the URL with the item's key value (usually:
        // the auto-incrementing numeric ID)
        if (is_null($this->item))
        {
            $this->item = $this->form->getModel();
        }

        $replace  = $this->item->getId();
        $ret = str_replace('[ITEM:ID]', $replace, $ret);

        // Replace the [ITEMID] in the URL with the current Itemid parameter
        $ret = str_replace('[ITEMID]', $this->form->getContainer()->input->getInt('Itemid', 0), $ret);

        // Replace the [TOKEN] in the URL with the Joomla! form token
        $ret = str_replace('[TOKEN]', \JFactory::getSession()->getFormToken(), $ret);

        // Replace other field variables in the URL
        $data = $this->item->getData();

        foreach ($data as $field => $value)
        {
            // Skip non-processable values
            if(is_array($value) || is_object($value))
            {
                continue;
            }

            $search = '[ITEM:' . strtoupper($field) . ']';
            $ret    = str_replace($search, $value, $ret);
        }

        return $ret;
    }

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   4.0
	 */
	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', (string) $option['value'],
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text'
			);
		}

		return $options;
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 *
	 * @since 4.0
	 */
	protected function getLayoutData()
	{
		$data = parent::getLayoutData();

		// Initialize some field attributes.
		$maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
		$inputmode    = !empty($this->inputmode) ? ' inputmode="' . $this->inputmode . '"' : '';
		$dirname      = !empty($this->dirname) ? ' dirname="' . $this->dirname . '"' : '';

		$options  = (array) $this->getOptions();

		$extraData = array(
			'maxLength' => $maxLength,
			'pattern'   => $this->pattern,
			'inputmode' => $inputmode,
			'dirname'   => $dirname,
			'options'   => $options,
		);

		return array_merge($data, $extraData);
	}
}
