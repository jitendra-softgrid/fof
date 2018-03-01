<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

/**
 * Our fake class for testing the toolbar
 *
 * Class JToolbarHelper
 */
class JToolbarHelper
{
    public static $methodCounter = array();

    public static function __callStatic($name, $args)
    {
        if(isset(self::$methodCounter[$name]))
        {
            self::$methodCounter[$name]++;
        }
        else
        {
            self::$methodCounter[$name] = 1;
        }
    }

    public static function resetMethods()
    {
        self::$methodCounter = array();
    }
}
