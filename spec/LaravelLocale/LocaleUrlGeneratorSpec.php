<?php

namespace spec\KiaKing\LaravelLocale;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Config\Repository as Config;

class LocaleUrlGeneratorSpec extends ObjectBehavior
{
    function let(Config $config, Request $request, UrlGenerator $url)
    {
        $this->beConstructedWith($config, $request, $url);

        $config->get('locale.available_locales')->willReturn(['ja', 'en', 'fr']);
        $config->get('app.fallback_locale')->willReturn('ja');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('KiaKing\LaravelLocale\LocaleUrlGenerator');
    }

    function it_passes_non_prefixed_url_to_url_when_locale_is_default(Config $config, UrlGenerator $url)
    {
        $config->get('app.locale')->willReturn('ja');
        $url->to('home', [], null)->shouldBeCalled();

        $this->url('home');
    }

    function it_passes_prefixed_url_to_url_when_locale_is_not_default(Config $config, UrlGenerator $url)
    {
        $config->get('app.locale')->willReturn('en');
        $url->to('en/home', [], null)->shouldBeCalled();

        $this->url('home');
    }

    function it_passes_non_prefixed_named_route_to_url_when_locale_is_default(Config $config, UrlGenerator $url)
    {
        $config->get('app.locale')->willReturn('ja');
        $url->route('home', [], true)->shouldBeCalled();

        $this->route('home');
    }

    function it_passes_prefixed_named_route_to_url_when_locale_is_not_default(Config $config, UrlGenerator $url)
    {
        $config->get('app.locale')->willReturn('en');
        $url->route('en.home', [], true)->shouldBeCalled();

        $this->route('home');
    }
}
