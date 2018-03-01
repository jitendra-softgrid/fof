<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Factory\Scaffolding\Layout;

use FOF40\Inflector\Inflector;
use FOF40\Model\DataModel;

/**
 * Erects a scaffolding XML for read views
 *
 * @package FOF40\Factory\Scaffolding
 *
 * @deprecated 3.1  Support for XML forms will be removed in FOF 4
 */
class ItemErector extends FormErector implements ErectorInterface
{
	public function build()
	{
		$this->addDescriptions = false;

		parent::build();

		$this->xml->addAttribute('type', 'read');

		$this->pushResults();
	}
}
