<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF40\Factory\Scaffolding\View;

use FOF40\View\DataView\Html;

/**
 * Interface ErectorInterface
 */
interface ErectorInterface
{
	/**
	 * Construct the erector object
	 *
	 * @param   Builder  $parent   The parent builder
	 * @param   Html     $view     The controller we're erecting a scaffold against
	 * @param   string   $viewName The view name for this view
	 * @param   string   $viewType The view type for this view
	 */
	public function __construct(Builder $parent, Html $view, $viewName, $viewType);

	/**
	 * Erects a scaffold. It then uses the parent's methods to assign the erected scaffold.
	 *
	 * @return  void
	 */
	public function build();

    /**
     * @return string
     */
    public function getSection();

    /**
     * @param string $section
     */
    public function setSection($section);
}