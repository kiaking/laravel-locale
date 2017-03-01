<?php

namespace Tests;

use KiaKing\LaravelLocale\Manager;

class ManagerTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.fallback_locale', 'en');
    }

    /** @test */
    public function it_gets_default_locale_value()
    {
        $manager = \App::make(Manager::class);

        $result = $manager->getDefaultLocale();

        $this->assertEquals($result, 'en');
    }

    /** @test */
    public function it_sets_locale()
    {
        $manager = \App::make(Manager::class);

        $result = $manager->setLocale('ja');

        $this->assertEquals(\App::getLocale(), 'ja');
    }
}
