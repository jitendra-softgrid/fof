<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Field;

use DateTimeZone;
use FOF40\Date\Date;
use FOF40\Date\DateDecorator;
use FOF40\Form\FieldInterface;
use FOF40\Form\FormField;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use JText;
use SimpleXMLElement;

defined('_JEXEC') or die;

/**
 * Form Field class for the FOF framework
 * Supports a calendar / date field.
 */
class Calendar extends FormField implements FieldInterface
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $type = 'Calendar';

	/**
	 * The allowable maxlength of calendar field.
	 *
	 * @var    integer
	 * @since  4.0
	 */
	protected $maxlength;

	/**
	 * The format of date and time.
	 *
	 * @var    integer
	 * @since  4.0
	 */
	protected $format;

	/**
	 * The filter.
	 *
	 * @var    integer
	 * @since  4.0
	 */
	protected $filter;

	/**
	 * The minimum year number to subtract/add from the current year
	 *
	 * @var    integer
	 * @since  4.0
	 */
	protected $minyear;

	/**
	 * The maximum year number to subtract/add from the current year
	 *
	 * @var    integer
	 * @since  4.0
	 */
	protected $maxyear;

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $layout = 'joomla.form.field.calendar';

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string $name The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   4.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'maxlength':
			case 'format':
			case 'filter':
			case 'timeformat':
			case 'todaybutton':
			case 'singleheader':
			case 'weeknumbers':
			case 'showtime':
			case 'filltable':
			case 'minyear':
			case 'maxyear':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string $name  The property name for which to the the value.
	 * @param   mixed  $value The value of the property.
	 *
	 * @return  void
	 *
	 * @since   4.0
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'maxlength':
			case 'timeformat':
				$this->$name = (int) $value;
				break;
			case 'todaybutton':
			case 'singleheader':
			case 'weeknumbers':
			case 'showtime':
			case 'filltable':
			case 'format':
			case 'filter':
			case 'minyear':
			case 'maxyear':
				$this->$name = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement $element   The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed            $value     The form field value to validate.
	 * @param   string           $group     The field name group control value. This acts as an array container for the field.
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
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$this->maxlength    = (int) $this->element['maxlength'] ? (int) $this->element['maxlength'] : 45;
			$this->format       = (string) $this->element['format'] ? (string) $this->element['format'] : '%Y-%m-%d';
			$this->filter       = (string) $this->element['filter'] ? (string) $this->element['filter'] : 'USER_UTC';
			$this->todaybutton  = (string) $this->element['todaybutton'] ? (string) $this->element['todaybutton'] : 'true';
			$this->weeknumbers  = (string) $this->element['weeknumbers'] ? (string) $this->element['weeknumbers'] : 'true';
			$this->showtime     = (string) $this->element['showtime'] ? (string) $this->element['showtime'] : 'false';
			$this->filltable    = (string) $this->element['filltable'] ? (string) $this->element['filltable'] : 'true';
			$this->timeformat   = (int) $this->element['timeformat'] ? (int) $this->element['timeformat'] : 24;
			$this->singleheader = (string) $this->element['singleheader'] ? (string) $this->element['singleheader'] : 'false';
			$this->minyear      = (string) $this->element['minyear'] ? (string) $this->element['minyear'] : null;
			$this->maxyear      = (string) $this->element['maxyear'] ? (string) $this->element['maxyear'] : null;

			if ($this->maxyear < 0 || $this->minyear > 0)
			{
				$this->todaybutton = 'false';
			}

			// Show time by default in the FOF Calendar field
			$showTimeValue = (string) $this->element['showtime'];

			if (empty($showTimeValue))
			{
				$this->showtime = true;
			}
		}

		return $return;
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
		$config = JFactory::getConfig();
		$user   = JFactory::getUser();

		// Translate the format if requested
		$translateFormat = (string) $this->element['translateformat'];

		if ($translateFormat && $translateFormat != 'false')
		{
			$showTime = (string) $this->element['showtime'];

			if ($showTime && $showTime != 'false')
			{
				$this->format = JText::_('DATE_FORMAT_CALENDAR_DATETIME');
			}
			else
			{
				$this->format = JText::_('DATE_FORMAT_CALENDAR_DATE');
			}
		}

		// If a known filter is given use it.
		switch (strtoupper($this->filter))
		{
			case 'SERVER_UTC':
				// Convert a date to UTC based on the server timezone.
				if ($this->value && $this->value != JFactory::getDbo()->getNullDate())
				{
					// Get a date object based on the correct timezone.
					$date = JFactory::getDate($this->value, 'UTC');
					$date->setTimezone(new DateTimeZone($config->get('offset')));

					// Transform the date string.
					$this->value = $date->format('Y-m-d H:i:s', true, false);
				}
				break;
			case 'USER_UTC':
				// Convert a date to UTC based on the user timezone.
				if ($this->value && $this->value != JFactory::getDbo()->getNullDate())
				{
					// Get a date object based on the correct timezone.
					$date = JFactory::getDate($this->value, 'UTC');
					$date->setTimezone($user->getTimezone());

					// Transform the date string.
					$this->value = $date->format('Y-m-d H:i:s', true, false);
				}
				break;
		}

		// Format value when not nulldate ('0000-00-00 00:00:00'), otherwise blank it as it would result in 1970-01-01.
		if ($this->value && $this->value != JFactory::getDbo()->getNullDate() && strtotime($this->value) !== false)
		{
			$tz = date_default_timezone_get();
			date_default_timezone_set('UTC');
			$this->value = strftime($this->format, strtotime($this->value));
			date_default_timezone_set($tz);
		}
		else
		{
			$this->value = '';
		}

		return $this->getRenderer($this->layout)->render($this->getLayoutData());
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 *
	 * @since  4.0
	 */
	protected function getLayoutData()
	{
		$data      = parent::getLayoutData();
		$tag       = JFactory::getLanguage()->getTag();
		$calendar  = JFactory::getLanguage()->getCalendar();
		$direction = strtolower(JFactory::getDocument()->getDirection());

		// Get the appropriate file for the current language date helper
		$helperPath = 'system/fields/calendar-locales/date/gregorian/date-helper.min.js';

		if (!empty($calendar) && is_dir(JPATH_ROOT . '/media/system/js/fields/calendar-locales/date/' . strtolower($calendar)))
		{
			$helperPath = 'system/fields/calendar-locales/date/' . strtolower($calendar) . '/date-helper.min.js';
		}

		// Get the appropriate locale file for the current language
		$localesPath = 'system/fields/calendar-locales/en.js';

		if (is_file(JPATH_ROOT . '/media/system/js/fields/calendar-locales/' . strtolower($tag) . '.js'))
		{
			$localesPath = 'system/fields/calendar-locales/' . strtolower($tag) . '.js';
		}
		elseif (is_file(JPATH_ROOT . '/media/system/js/fields/calendar-locales/' . strtolower(substr($tag, 0, -3)) . '.js'))
		{
			$localesPath = 'system/fields/calendar-locales/' . strtolower(substr($tag, 0, -3)) . '.js';
		}

		$extraData = [
			'value'        => $this->value,
			'maxLength'    => $this->maxlength,
			'format'       => $this->format,
			'filter'       => $this->filter,
			'todaybutton'  => ($this->todaybutton === 'true') ? 1 : 0,
			'weeknumbers'  => ($this->weeknumbers === 'true') ? 1 : 0,
			'showtime'     => ($this->showtime === 'true') ? 1 : 0,
			'filltable'    => ($this->filltable === 'true') ? 1 : 0,
			'timeformat'   => $this->timeformat,
			'singleheader' => ($this->singleheader === 'true') ? 1 : 0,
			'helperPath'   => $helperPath,
			'localesPath'  => $localesPath,
			'minYear'      => $this->minyear,
			'maxYear'      => $this->maxyear,
			'direction'    => $direction,
		];

		return array_merge($data, $extraData);
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
		return $this->getCalendar('static');
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
		return $this->getCalendar('repeatable');
	}

	/**
	 * Method to get the calendar input markup.
	 *
	 * @param   string $display The display to render ('static' or 'repeatable')
	 *
	 * @return  string    The field input markup.
	 *
	 * @since   2.1.rc4
	 */
	protected function getCalendar($display)
	{
		// Initialize some field attributes.
		$format  = $this->format ? $this->format : '%Y-%m-%d';
		$class   = $this->class ? $this->class : '';
		$default = $this->element['default'] ? (string) $this->element['default'] : '';

		// Get some system objects.
		$config = $this->form->getContainer()->platform->getConfig();
		$user   = $this->form->getContainer()->platform->getUser();

		// Check for empty date values
		if (empty($this->value) || $this->value == $this->form->getContainer()->platform->getDbo()->getNullDate() || $this->value == '0000-00-00')
		{
			$this->value = $default;
		}

		// Handle the special case for "now".
		if (strtoupper($this->value) == 'NOW')
		{
			$this->value = strftime($format);
		}

		// If a known filter is given use it.
		switch (strtoupper($this->filter))
		{
			case 'SERVER_UTC':
				// Convert a date to UTC based on the server timezone.
				if ((int) $this->value)
				{
					// Get a date object based on the correct timezone.
					$coreObject = \JFactory::getDate($this->value, 'UTC');
					$date       = new DateDecorator($coreObject);

					$date->setTimezone(new \DateTimeZone($config->get('offset')));

					// Transform the date string.
					$this->value = $date->format('Y-m-d H:i:s', true, false);
				}

				break;

			case 'USER_UTC':
				// Convert a date to UTC based on the user timezone.
				if ((int) $this->value)
				{
					// Get a date object based on the correct timezone.
					$coreObject = \JFactory::getDate($this->value, 'UTC');
					$date       = new DateDecorator($coreObject);

					$date->setTimezone(new \DateTimeZone($user->getParam('timezone', $config->get('offset'))));

					// Transform the date string.
					$this->value = $date->format('Y-m-d H:i:s', true, false);
				}

				break;
		}

		if ($display == 'static')
		{
			// Build the attributes array.
			$attributes = [];

			if ($this->placeholder)
			{
				$attributes['placeholder'] = $this->placeholder;
			}

			if ($this->class)
			{
				$attributes['class'] = $this->class;
			}

			if ($this->size)
			{
				$attributes['size'] = $this->size;
			}

			if ($this->maxlength)
			{
				$attributes['maxlength'] = $this->maxlength;
			}

			if ($this->class)
			{
				$attributes['class'] = $this->class;
			}

			if ($this->readonly)
			{
				$attributes['readonly'] = 'readonly';
			}

			if ($this->disabled)
			{
				$attributes['disabled'] = 'disabled';
			}

			if ($this->onchange)
			{
				$attributes['onChange'] = $this->onchange;
			}

			if ($this->autocomplete)
			{
				$attributes['autocomplete'] = $this->autocomplete;
			}

			if ($this->autofocus)
			{
				$attributes['autofocus'] = $this->autofocus;
			}

			if ($this->filter)
			{
				$attributes['filter'] = $this->filter;
			}

			if ($this->today)
			{
				$attributes['todayBtn'] = $this->today;
			}

			if ($this->weeknumbers)
			{
				$attributes['weekNumbers'] = $this->weeknumbers;
			}

			if ($this->showtime)
			{
				$attributes['showTime'] = in_array(strtolower($this->showtime), ['true', '1', 'on', 'yes']);
			}
			elseif ($this->time)
			{
				$attributes['showTime'] = in_array(strtolower($this->time), ['true', '1', 'on', 'yes']);
			}

			if ($this->filltable)
			{
				$attributes['fillTable'] = in_array(strtolower($this->filltable), ['true', '1', 'on', 'yes']);
			}

			if ($this->timeformat)
			{
				$attributes['timeFormat'] = $this->timeformat;
			}

			if ($this->singleheader)
			{
				$attributes['singleHeader'] = in_array(strtolower($this->singleheader), ['true', '1', 'on', 'yes']);
			}

			if ($this->required)
			{
				$attributes['required']      = 'required';
				$attributes['aria-required'] = 'true';
			}

			// Including fallback code for HTML5 non supported browsers.
			JHtml::_('jquery.framework');
			JHtml::_('script', 'system/html5fallback.js', false, true);

			if (($format == '%Y-%m-%d') && isset($attributes['showTime']))
			{
				if ($attributes['showTime'])
				{
					$format = JText::_('DATE_FORMAT_CALENDAR_DATETIME');
				}
				else
				{
					$format = JText::_('DATE_FORMAT_CALENDAR_DATE');
				}
			}

			return JHtml::_('calendar', $this->value, $this->name, $this->id, $format, $attributes);
		}
		else
		{
			if (!$this->value
				&& (string) $this->element['empty_replacement'])
			{
				$replacement_key = (string) $this->element['empty_replacement'];
				$value           = \JText::_($replacement_key);
			}
			else
			{
				$date  = new Date($this->value);
				$value = strftime($format, $date->getTimestamp());
			}

			return '<span class="' . $this->id . ' ' . $class . '">' .
				htmlspecialchars($value, ENT_COMPAT, 'UTF-8') .
				'</span>';
		}
	}
}
