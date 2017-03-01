<?php

namespace KiaKing\LaravelLocale\Facades;

use KiaKing\LaravelLocale\Router;
use Illuminate\Support\Facades\Facade;

class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return Router::class; }
}
