<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Generator\Command\Generate\Model;

use FOF40\Generator\Command\Command;
use FOF40\Factory\Scaffolding\Model\Builder as ModelBuilder;
use FOF40\Container\Container;

class Model extends Command
{
    public function execute()
    {
        // Backend or frontend?
        $section = $this->input->get('frontend', false) ? 'site' : 'admin';
        $view    = $this->getViewName($this->input);

        // Let's force the use of the Magic Factory
        $container = Container::getInstance($this->component, array('factoryClass' => 'FOF40\\Factory\\MagicFactory'));
        $container->factory->setSaveScaffolding(true);

        $view = $container->inflector->pluralize($view);

        $classname = $container->getNamespacePrefix($section).'Model\\'.ucfirst($view);

        $scaffolding = new ModelBuilder($container);
        $scaffolding->setSection($section);

        if(!$scaffolding->make($classname, $view))
        {
            throw new \RuntimeException("An error occurred while creating the Model class");
        }
    }
}
