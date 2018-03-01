<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Form\Exception;

use Exception;

defined('_JEXEC') or die;

/**
 * Class GetInputNotAllowed
 */
class GetInputNotAllowed extends \LogicException
{
	public function __construct($className, $code = 0, Exception $previous = null)
	{
		$message = \JText::sprintf('LIB_FOF_FORM_ERR_GETINPUT_NOT_ALLOWED', $className);

		parent::__construct($message, $code, $previous);
	}
}
