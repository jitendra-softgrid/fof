<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF30\Generator\Command\Generate\Layout;

use FOF30\Generator\Command\Command as Command;

class Layouts extends LayoutBase
{
	public function execute()
    {
		$this->createView('default');
		$this->createView('form');
		$this->createView('item');
	}
}
