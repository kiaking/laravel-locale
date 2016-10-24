<?php

namespace spec\KiaKing\LaravelLocale;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\Registrar as Router;

class LocaleRouterSpec extends ObjectBehavior
{
    public function loopTest(Config $config, Router $router, $method)
    {
        $router->{$method}('home', 'HomeController@index')->shouldBeCalled();
        $router->{$method}('ja/home', 'HomeController@index')->shouldBeCalled();
        $router->{$method}('en/home', 'HomeController@index')->shouldBeCalled();
        $router->{$method}('fr/home', 'HomeController@index')->shouldBeCalled();

        $router->{$method}('home', ['as' => 'home', 'uses' => 'HomeController@index'])
               ->shouldBeCalled();

        $router->{$method}('ja/home', ['as' => 'ja.home', 'uses' => 'HomeController@index'])
               ->shouldBeCalled();

        $router->{$method}('en/home', ['as' => 'en.home', 'uses' => 'HomeController@index'])
               ->shouldBeCalled();

        $router->{$method}('fr/home', ['as' => 'fr.home', 'uses' => 'HomeController@index'])
               ->shouldBeCalled();

        $this->{$method}('home', 'HomeController@index');
        $this->{$method}('home', ['as' => 'home', 'uses' => 'HomeController@index']);
    }

    function let(Config $config, Router $router)
    {
        $this->beConstructedWith($config, $router);

        $config->get('locale.available_locales')->willReturn(['ja', 'en', 'fr']);
        $config->get('app.fallback_locale')->willReturn('ja');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('KiaKing\LaravelLocale\LocaleRouter');
    }

    function it_can_generate_get_routes(Config $config, Router $router)
    {
        $this->loopTest($config, $router, 'get');
    }

    function it_can_generate_post_routes(Config $config, Router $router)
    {
        $this->loopTest($config, $router, 'post');
    }

    function it_can_generate_put_routes(Config $config, Router $router)
    {
        $this->loopTest($config, $router, 'put');
    }

    function it_can_generate_patch_routes(Config $config, Router $router)
    {
        $this->loopTest($config, $router, 'patch');
    }

    function it_can_generate_delete_routes(Config $config, Router $router)
    {
        $this->loopTest($config, $router, 'delete');
    }
}
