<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Generator\Command\Generate\Mvc;

use FOF40\Generator\Command\Command;
use FOF40\Generator\Command\Generate\Controller\Controller;
use FOF40\Generator\Command\Generate\Model\Model;
use FOF40\Generator\Command\Generate\View\View;

class Mvc extends Command
{
    public function execute()
    {
        $controller = new Controller($this->composer, $this->input);
        $controller->execute();

        $controller = new Model($this->composer, $this->input);
        $controller->execute();

        $controller = new View($this->composer, $this->input);
        $controller->execute();
    }
}
