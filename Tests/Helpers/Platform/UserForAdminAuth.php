<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF30\Tests\Helpers\Platform;


class UserForAdminAuth extends \JUser
{
	public $allowedAuths = array(); // e.g. core.admin#com_foobar

	public function authorise($action, $assetname = null)
	{
		$signature = $action . (is_null($assetname) ? '' : ('#' . $assetname));

		return in_array($signature, $this->allowedAuths);
	}
}
