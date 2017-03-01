<?php

namespace KiaKing\LaravelLocale\Facades;

use Illuminate\Support\Facades\Facade;
use KiaKing\LaravelLocale\UrlGenerator;

class URL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return UrlGenerator::class; }
}
