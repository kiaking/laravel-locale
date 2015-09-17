<?php

namespace KiaKing\LaravelLocale;

use Illuminate\Support\Facades\Facade;

class LocaleFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'locale'; }
}
