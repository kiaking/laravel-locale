<?php

namespace spec\KiaKing\LaravelLocale;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;

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

    function it_can_change_url_to_same_locale(Config $config, Request $request)
    {
        $config->get('app.locale')->willReturn('ja');
        $request->fullUrl()->willReturn('http://example.com/?query=value');

        $this->change('ja')->shouldReturn('http://example.com/?query=value');
    }

    function it_can_change_url_to_other_locale_from_default(Config $config, Request $request)
    {
        $config->get('app.locale')->willReturn('ja');
        $request->root()->willReturn('http://example.com');
        $request->fullUrl()->willReturn('http://example.com/?query=value');

        $this->change('en')->shouldReturn('http://example.com/en/?query=value');
    }

    function it_can_change_url_to_default_locale_from_non_default_locale(Config $config, Request $request)
    {
        $config->get('app.locale')->willReturn('en');
        $request->fullUrl()->willReturn('http://example.com/en/?query=value');

        $this->change('ja')->shouldReturn('http://example.com/?query=value');
    }

    function it_can_change_url_to_other_locale_from_non_default_locale(Config $config, Request $request)
    {
        $config->get('app.locale')->willReturn('en');
        $request->fullUrl()->willReturn('http://example.com/en/?query=value');

        $this->change('fr')->shouldReturn('http://example.com/fr/?query=value');
    }
}
