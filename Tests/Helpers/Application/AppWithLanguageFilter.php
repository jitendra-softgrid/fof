<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2018 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

namespace FOF30\Tests\Helpers\Application;

class AppWithLanguageFilter
{
    protected $languageFilter;

    public function __construct($filter)
    {
        $this->languageFilter = $filter;
    }

    public function getLanguageFilter()
    {
        return $this->languageFilter;
    }
}
