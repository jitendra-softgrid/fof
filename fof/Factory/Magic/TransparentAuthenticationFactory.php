<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Factory\Magic;

use FOF40\Dispatcher\Dispatcher;
use FOF40\Model\DataModel;
use FOF40\View\DataView\DataViewInterface;

defined('_JEXEC') or die;

/**
 * Creates a TransparentAuthentication object instance based on the information provided by the fof.xml configuration file
 */
class TransparentAuthenticationFactory extends BaseFactory
{
	/**
	 * Create a new object instance
	 *
	 * @param   array   $config    The config parameters which override the fof.xml information
	 *
	 * @return  Dispatcher  A new Dispatcher object
	 */
	public function make(array $config = array())
	{
		$appConfig = $this->container->appConfig;
		$defaultConfig = $appConfig->get('authentication.*');
		$config = array_merge($defaultConfig, $config);

		$className = $this->container->getNamespacePrefix($this->getSection()) . 'TransparentAuthentication\\DefaultTransparentAuthentication';

		if (!class_exists($className, true))
		{
			$className = '\\FOF40\\TransparentAuthentication\\TransparentAuthentication';
		}

		$dispatcher = new $className($this->container, $config);

		return $dispatcher;
	}
}
