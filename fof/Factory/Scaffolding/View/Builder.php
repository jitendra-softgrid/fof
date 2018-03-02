<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Factory\Scaffolding\View;

use FOF40\Container\Container;
use FOF40\Factory\Magic\ViewFactory;

/**
 * Scaffolding Builder
 *
 * @package FOF40\Factory\Scaffolding
 */
class Builder
{
	/** @var  \FOF40\Container\Container  The container we belong to */
	protected $container = null;

    /**
     * Section used to build the namespace prefix. We have to pass it since in CLI scaffolding we need
     * to force the section we're in (ie Site or Admin). {@see \FOF40\Container\Container::getNamespacePrefix() } for valid values
     *
     * @var   string
     */
    protected $section = 'auto';

	/**
	 * Create the scaffolding builder instance
	 *
	 * @param \FOF40\Container\Container $c
	 */
	public function __construct(Container $c)
	{
		$this->container = $c;
	}

	/**
	 * Make a new scaffolding document
	 *
	 * @param   string  $requestedClass     The requested class, with full qualifier ie Myapp\Site\Controller\Foobar
	 * @param   string  $viewName           The name of the view linked to this controller
	 * @param   string  $viewType           The type of the view linked to this controller
	 *
	 * @return  bool    True on success, false otherwise
	 */
	public function make($requestedClass, $viewName, $viewType)
	{
        // Class already exists? Stop here
		if (class_exists($requestedClass))
        {
            return true;
        }

        // I have to magically create the controller class
        $magic   = new ViewFactory($this->container);
        $magic->setSection($this->getSection());
        $fofView = $magic->make($viewName, $viewType);

		/** @var ErectorInterface $erector */
        $erector = new ViewErector($this, $fofView, $viewName, $viewType);
        $erector->setSection($this->getSection());
		$erector->build();

        if(!class_exists($requestedClass))
        {
            return false;
        }

        return true;
	}

	/**
	 * Gets the container this builder belongs to
	 *
	 * @return Container
	 */
	public function getContainer()
	{
		return $this->container;
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