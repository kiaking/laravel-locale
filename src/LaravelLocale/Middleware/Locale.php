<?php

namespace KiaKing\LaravelLocale\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory as Response;
use Illuminate\Contracts\Routing\Middleware;
use KiaKing\LaravelLocale\LocaleUrlGenerator as Url;

class Locale implements Middleware
{
    /**
     * The config implementation.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The response implementation.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * The locale url generator implementation.
     *
     * @var \KiaKing\LaravelLocale\LocaleUrlGenerator
     */
    protected $url;

    /**
     * Create a new Locale Middleware instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository $config
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param  \KiaKing\LaravelLocale\LocaleUrlGenerator $url
     * @return void
     */
    public function __construct(Config $config, Response $response, Url $url)
    {
        $this->config = $config;
        $this->response = $response;
        $this->url = $url;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);
        $default = $this->config->get('app.fallback_locale');

        if (in_array($locale, $this->config->get('locale.available_locales'))) {
            $this->config->set('app.locale', $locale);
        }

        if ($request->switch_locale_to) {
            return $this->redirect($request->switch_locale_to);
        }

        $userLocale = $request->cookie('locale') ? $request->cookie('locale') : substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if ($userLocale != $this->config->get('app.locale')) {
            return $this->redirect($userLocale);
        }

        if ($locale == $default) {
            return $this->response->redirectTo($this->url->urlFor($default));
        }

        return $next($request);
    }

    protected function redirect($locale)
    {
        return $this->response
            ->redirectTo($this->url->urlFor($locale))
            ->withCookie('locale', $locale, 525600);
    }
}
