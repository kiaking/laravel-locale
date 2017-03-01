<?php

namespace Tests;
use Mockery;
use Illuminate\Http\Request;
use KiaKing\LaravelLocale\Manager;
use Illuminate\Contracts\Config\Repository as Config;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public $config;
    public $request;
    public $manager;

    public function setup()
    {
        $this->config = Mockery::mock(Config::class);
        $this->request = Mockery::mock(Request::class);
        $this->manager = new Manager($this->config, $this->request);
    }

    public function teardown()
    {
        Mockery::close();
    }

    /** @test */
    public function it_gets_default_locale_value()
    {
        $this->config->shouldReceive('get')
            ->with('app.fallback_locale')
            ->andReturn('ja')
            ->once();

        $result = $this->manager->getDefaultLocale();

        $this->assertEquals($result, 'ja');
    }
}
