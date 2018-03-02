<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Generator\Command\Generate\Layout;

use FOF40\Generator\Command\Command as Command;

class ItemLayout extends LayoutBase
{
	public function execute()
    {
		$this->createView('item');
	}
}