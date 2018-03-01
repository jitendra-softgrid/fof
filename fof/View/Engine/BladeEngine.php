<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\View\Engine;

use FOF40\View\Compiler\Blade;
use FOF40\View\View;

defined('_JEXEC') or die;

/**
 * View engine for compiling PHP template files.
 */
class BladeEngine extends CompilingEngine implements EngineInterface
{
	public function __construct(View $view)
	{
		parent::__construct($view);

		// Assign the Blade compiler to this engine
		$this->compiler = $view->getContainer()->blade;
	}
}
