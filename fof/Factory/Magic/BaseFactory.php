<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Factory\Magic;

use FOF40\Container\Container;
use FOF40\Controller\DataController;
use FOF40\Factory\Exception\ControllerNotFound;

defined('_JEXEC') or die;

abstract class BaseFactory
{
	/**
	 * @var   Container|null  The container where this factory belongs to
	 */
	protected $container = null;

    /**
     * Section used to build the namespace prefix. We have to pass it since in CLI scaffolding we need
     * to force the section we're in (ie Site or Admin). {@see \FOF40\Container\Container::getNamespacePrefix() } for valid values
     *
     * @var   string
     */
    protected $section = 'auto';

	/**
	 * Public constructor
	 *
	 * @param   Container  $container  The container we belong to
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }
}
