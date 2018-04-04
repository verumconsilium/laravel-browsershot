<?php

namespace VerumConsilium\Browsershot\Facades;

use Illuminate\Support\Facades\Facade;

class PDF extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    protected static function getFacadeAccessor()
    {
        return 'browsershot.pdf';
    }
}
