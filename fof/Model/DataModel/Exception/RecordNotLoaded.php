<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Model\DataModel\Exception;

use Exception;

defined('_JEXEC') or die;

class RecordNotLoaded extends BaseException
{
	public function __construct( $message = "", $code = 404, Exception $previous = null )
	{
		if (empty($message))
		{
			$message = \JText::_('LIB_FOF_MODEL_ERR_COULDNOTLOAD');
		}

		parent::__construct( $message, $code, $previous );
	}

}
