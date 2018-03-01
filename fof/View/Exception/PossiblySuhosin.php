<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF30\View\Exception;

use Exception;

defined('_JEXEC') or die;

/**
 * Exception thrown when the access to the requested resource is forbidden under the current execution context
 */
class PossiblySuhosin extends \RuntimeException
{
	public function __construct( $message = "", $code = 403, Exception $previous = null )
	{
		if (empty($message))
		{
			$message = \JText::_('LIB_FOF_VIEW_POSSIBLYSUHOSIN');
		}

		parent::__construct( $message, $code, $previous );
	}

}
