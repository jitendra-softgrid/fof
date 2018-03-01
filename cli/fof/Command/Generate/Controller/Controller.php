<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Generator\Command\Generate\Controller;

use FOF40\Generator\Command\Command;
use FOF40\Factory\Scaffolding\Controller\Builder as ControllerBuilder;
use FOF40\Container\Container;

class Controller extends Command
{
    public function execute()
    {
        // Backend or frontend?
        $section = $this->input->get('frontend', false) ? 'site' : 'admin';
        $view    = $this->getViewName($this->input);

        // Let's force the use of the Magic Factory
        $container = Container::getInstance($this->component, array('factoryClass' => 'FOF40\\Factory\\MagicFactory'));
        $container->factory->setSaveScaffolding(true);

        // plural / singular
        $view = $container->inflector->singularize($view);

        $classname = $container->getNamespacePrefix($section).'Controller\\'.ucfirst($view);

        $scaffolding = new ControllerBuilder($container);
        $scaffolding->setSection($section);

        if(!$scaffolding->make($classname, $view))
        {
            throw new \RuntimeException("An error occurred while creating the Controller class");
        }
    }
}
