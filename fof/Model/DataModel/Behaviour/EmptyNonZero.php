<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Model\DataModel\Behaviour;

use FOF40\Event\Observer;
use FOF40\Model\DataModel;
use JDatabaseQuery;

defined('_JEXEC') or die;

/**
 * FOF model behavior class to let the Filters behaviour know that zero value is a valid filter value
 *
 * @since    2.1
 */
class EmptyNonZero extends Observer
{
	/**
	 * This event runs after we have built the query used to fetch a record
	 * list in a model. It is used to apply automatic query filters.
	 *
	 * @param   DataModel      &$model The model which calls this event
	 * @param   JDatabaseQuery &$query The query we are manipulating
	 *
	 * @return  void
	 */
	public function onAfterBuildQuery(&$model, &$query)
	{
		$model->setBehaviorParam('filterZero', 1);
	}
}
