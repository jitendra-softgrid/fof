<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Hal\Render;

defined('_JEXEC') or die;

/**
 * Interface for HAL document renderers
 *
 * @see http://stateless.co/hal_specification.html
 *
 * @codeCoverageIgnore
 */
interface RenderInterface
{
	/**
	 * Render a HAL document into a representation suitable for consumption.
	 *
	 * @param   array  $options  Renderer-specific options
	 *
	 * @return  string
	 */
	public function render($options = array());
}
