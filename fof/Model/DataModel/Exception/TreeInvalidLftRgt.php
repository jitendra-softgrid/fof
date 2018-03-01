<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Model\DataModel\Exception;

use Exception;

defined('_JEXEC') or die;

abstract class TreeInvalidLftRgt extends \RuntimeException
{
	public function __construct( $message = '', $code = 500, Exception $previous = null )
	{
		parent::__construct( $message, $code, $previous );
	}

}
