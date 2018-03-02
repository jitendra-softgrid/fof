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
use FOF40\Utils\StringHelper;
use \JHtml;
use Joomla\Utilities\ArrayHelper;
use \JText;
use SimpleXMLElement;

defined('_JEXEC') or die;

/**
 * Form Field class for FOF
 * Supports a generic list of options.
 */
class GenericList extends BaseList implements FieldInterface
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
	 * @since	Ordering is available since FOF 2.1.b2.
	 */
	protected function getOptions()
	{
		// Ordering is disabled by default for backward compatibility
		$order = false;

		// Set default order direction
		$order_dir = 'asc';

		// Set default value for case sensitive sorting
		$order_case_sensitive = false;

		if ($this->element['order'] && $this->element['order'] !== 'false')
		{
			$order = $this->element['order'];
		}

		if ($this->element['order_dir'])
		{
			$order_dir = $this->element['order_dir'];
		}

		if ($this->element['order_case_sensitive'])
		{
			// Override default setting when the form element value is 'true'
			if ($this->element['order_case_sensitive'] == 'true')
			{
				$order_case_sensitive = true;
			}
		}

		// Create a $sortOptions array in order to apply sorting
		$i = 0;
		$sortOptions = array();

		foreach ($this->element->children() as $option)
		{
			$name = JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname));

			$sortOptions[$i] = new \stdClass;
			$sortOptions[$i]->option = $option;
			$sortOptions[$i]->value = $option['value'];
			$sortOptions[$i]->name = $name;
			$i++;
		}

		// Only order if it's set
		if ($order)
		{
			jimport('joomla.utilities.arrayhelper');

			if (class_exists('JArrayHelper'))
			{
				\JArrayHelper::sortObjects($sortOptions, $order, $order_dir == 'asc' ? 1 : -1, $order_case_sensitive, false);
			}
			else
			{
				ArrayHelper::sortObjects($sortOptions, $order, $order_dir == 'asc' ? 1 : -1, $order_case_sensitive, false);
			}
		}

		// Initialise the options
		$options = array();

		// Get the field $options
		foreach ($sortOptions as $sortOption)
		{
			/** @var \SimpleXMLElement $option */
			$option = $sortOption->option;
			$name = $sortOption->name;

			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			$tmp = JHtml::_('select.option', (string) $option['value'], $name, 'value', 'text', ((string) $option['disabled'] == 'true'));

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Do we have a class and method source for our options?
		$source_file      = empty($this->element['source_file']) ? '' : (string) $this->element['source_file'];
		$source_class     = empty($this->element['source_class']) ? '' : (string) $this->element['source_class'];
		$source_method    = empty($this->element['source_method']) ? '' : (string) $this->element['source_method'];
		$source_key       = empty($this->element['source_key']) ? '*' : (string) $this->element['source_key'];
		$source_value     = empty($this->element['source_value']) ? '*' : (string) $this->element['source_value'];
		$source_translate = is_null($this->element['source_translate']) ? 'true' : (string) $this->element['source_translate'];
		$source_translate = StringHelper::toBool($source_translate) ? true : false;
		$source_format    = empty($this->element['source_format']) ? '' : (string) $this->element['source_format'];

		if ($source_class && $source_method)
		{
			// Maybe we have to load a file?
			if (!empty($source_file))
			{
				$source_file = $this->form->getContainer()->template->parsePath($source_file, true);

				if ($this->form->getContainer()->filesystem->fileExists($source_file))
				{
					include_once $source_file;
				}
			}

			// Make sure the class exists
			if (class_exists($source_class, true))
			{
				// ...and so does the option
				if (in_array($source_method, get_class_methods($source_class)))
				{
					// Get the data from the class
					if ($source_format == 'optionsobject')
					{
						$options = array_merge($options, $source_class::$source_method());
					}
					else
					{
						// Get the data from the class
						$source_data = $source_class::$source_method();

						// Loop through the data and prime the $options array
						foreach ($source_data as $k => $v)
						{
							$key = (empty($source_key) || ($source_key == '*')) ? $k : @$v[$source_key];
							$value = (empty($source_value) || ($source_value == '*')) ? $v : @$v[$source_value];

							if ($source_translate)
							{
								$value = JText::_($value);
							}

							$options[] = JHtml::_('select.option', $key, $value, 'value', 'text');
						}
					}
				}
			}
		}

		reset($options);

		return $options;
	}
}
