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
 * Class InvalidGroupContents
 */
class InvalidGroupContents extends \InvalidArgumentException
{
	public function __construct($className, $code = 1, Exception $previous = null)
	{
		$message = \JText::sprintf('LIB_FOF_FORM_ERR_GETOPTIONS_INVALID_GROUP_CONTENTS', $className);

		parent::__construct($message, $code, $previous);
	}
}
