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
use JText;

defined('_JEXEC') or die;

\JFormHelper::loadFieldClass('number');

/**
 * Backwards compatibility field. DO NOT USE IN PHP 7.2 AND LATER.
 *
 * @deprecated 3.1  Support for XML forms will be removed in FOF 4
 */
class Numeric extends Number
{
	public function __construct($form = null)
	{
		parent::__construct($form);

		$this->form->getModel()->getContainer()->platform->logDeprecated("The numeric field is deprecated and may cause fatal errors in PHP 7.2 and later. Use the number field type instead.");
	}

}
