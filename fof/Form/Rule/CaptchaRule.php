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
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Captcha\Captcha;
use Joomla\Registry\Registry;
use RuntimeException;

/**
 * Form Rule class for the Joomla Framework.
 *
 * @since 4.0
 */
class CaptchaRule extends FormRule
{
	/**
	 * Method to test if the Captcha is correct.
	 *
	 * @param   \SimpleXMLElement $element   The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value     The form field value to validate.
	 * @param   string            $group     The field name group control value. This acts as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   Registry          $input     An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form              $form      The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   4.0
	 *
	 * @throws \Exception
	 */
	public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		$app    = JFactory::getApplication();
		$plugin = $app->get('captcha');

		if ($app->isClient('site'))
		{
			/** @var SiteApplication $app */
			$plugin = $app->getParams()->get('captcha', $plugin);
		}

		$namespace = $element['namespace'] ?: $form->getName();

		// Use 0 for none
		if ($plugin === 0 || $plugin === '0')
		{
			return true;
		}
		else
		{
			$captcha = Captcha::getInstance((string) $plugin, array('namespace' => (string) $namespace));
		}

		// Test the value.
		if (!$captcha->checkAnswer($value))
		{
			$error = $captcha->getError();

			if ($error instanceof \Exception)
			{
				return $error;
			}

			throw new RuntimeException($error);
		}

		return true;
	}
}
