<?php

namespace NikolayOskin\Contents;

use Illuminate\Support\Facades\Facade;

class ContentsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'contents';
    }
}
