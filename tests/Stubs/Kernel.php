<?php

namespace Tests\Stubs;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'locale' => \KiaKing\LaravelLocale\Middleware\SetLocale::class,
        'locale.redirect' => \KiaKing\LaravelLocale\Middleware\RedirectIfLocaleMismatch::class,
    ];
}
