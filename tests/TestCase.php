<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [\KiaKing\LaravelLocale\LaravelLocaleServiceProvider::class];
    }
}
