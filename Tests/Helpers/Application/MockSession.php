<?php
/**
 * @package     FOF
 * @copyright   2010-2017 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Application;

use FOF30\Tests\Helpers\FOFTestCase;

class MockSession
{
	/**
	 * An array of options.
	 *
	 * @var    array
	 * @since  11.3
	 */
	protected static $options = array();

	/**
	 * Gets an option.
	 *
	 * @param   string  $name     The name of the option.
	 * @param   string  $default  The default value to use if the option is not found.
	 *
	 * @return  mixed  The value of the option, or the default if not found.
	 *
	 * @since   11.3
	 */
	public static function getOption($name, $default = null)
	{
		return isset(self::$options[$name]) ? self::$options[$name] : $default;
	}

	/**
	 * Creates an instance of the mock JSession object.
	 *
	 * @param   FOFTestCase  $test     A test object.
	 * @param   array                       $options  An array of optional configuration values.
	 *                                                getId : the value to be returned by the mock getId method
	 *                                                get.user.id : the value to assign to the user object id returned by get('user')
	 *                                                get.user.name : the value to assign to the user object name returned by get('user')
	 *                                                get.user.username : the value to assign to the user object username returned by get('user')
	 *
	 * @return  \PHPUnit_Framework_MockObject_MockObject
	 *
	 * @since   11.3
	 */
	public static function create($test, $options = array())
	{
		if (is_array($options))
		{
			self::$options = $options;
		}

		// Mock all the public methods.
		$methods = array(
			'clear',
			'close',
			'destroy',
			'fork',
			'get',
			'getExpire',
			'getFormToken',
			'getId',
			'getInstance',
			'getName',
			'getState',
			'getStores',
			'getToken',
			'has',
			'hasToken',
			'getPrefix',
			'isNew',
			'restart',
			'set',
		);

		// Create the mock.
		$mockObject = $test->getMockBuilder('\JSession')
			->setMethods($methods)
			->setConstructorArgs(array())
			->setMockClassName('')
			->disableOriginalConstructor()
			->getMock();

		// Mock selected methods.
		$test->assignMockReturns(
			$mockObject, array(
				'getId' => self::getOption('getId')
			)
		);

		$test->assignMockCallbacks(
			$mockObject,
			array(
				'get' => array(get_called_class(), 'mockGet'),
			)
		);

		return $mockObject;
	}

	/**
	 * Mocking the get method.
	 *
	 * @param   string  $key  The key to get.
	 *
	 * @return  mixed
	 *
	 * @since   11.3
	 */
	public function mockGet($key)
	{
		switch ($key)
		{
			case 'user':
				// Attempt to load JUser.
				class_exists('JUser');

				$user = new \JUser;

				$user->id = (int) self::getOption('get.user.id', 0);
				$user->name = self::getOption('get.user.name');
				$user->username = self::getOption('get.user.username');
				$user->guest = (int) self::getOption('get.user.guest', 1);

				return $user;
		}

		return null;
	}
}
