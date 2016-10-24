<?php

namespace KiaKing\LaravelLocale\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory as Response;
use KiaKing\LaravelLocale\Manager;
use KiaKing\LaravelLocale\LocaleUrlGenerator as Url;

class Locale
{
    /**
     * The locale manager implementation.
     *
     * @var \KiaKing\LaravelLocale\Manager
     */
    protected $manager;

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
     * @param  \KiaKing\LaravelLocale\Manager  $manager
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param  \KiaKing\LaravelLocale\LocaleUrlGenerator  $url
     * @return void
     */
    public function __construct(Manager $manager, Response $response, Url $url)
    {
        $this->manager = $manager;
        $this->response = $response;
        $this->url = $url;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  bool  $autoDetect
     * @return mixed
     */
    public function handle($request, Closure $next, $autoDetect = false)
    {
        // If user requested to change locale, redirect them to proper page.
        if ($this->manager->localeSwitchRequest()) {
            return $this->redirect($this->manager->getSwitchrequestLocale(), $autoDetect);
        }

        // If accessing user agent is web crawler then do not auto detect.
        if ($autoDetect && $this->manager->isWebCrawler()) {
            $autoDetect = false;
        }

        // When auto detection is on and if user's locale and URI locale
        // doesn't matches redirect them.
        if ($autoDetect && ($this->manager->getUriLocale() != $this->manager->getUserLocale())) {
            return $this->redirect($this->manager->getUserLocale(), $autoDetect);
        }

        // If the first segment of URI is same as default locale redirect them.
        if ($request->segment(1) == $this->manager->getDefaultLocale()) {
            $locale = $autoDetect ? $this->manager->getUserLocale() : $this->manager->getDefaultLocale();

            return $this->redirect($locale, $autoDetect);
        }

        $this->manager->setLocale($this->manager->getUriLocale());

        return $next($request);
    }

    /**
     * Redirect to specific locale page and set cookie to remember their
     * setting.
     *
     * @param  string  $locale
     * @param  bool  $autoDetect
     * @return Response
     */
    protected function redirect($locale, $autoDetect = false)
    {
        $response = $this->response->redirectTo($this->url->urlFor($locale));

        if ($autoDetect) {
            $response = $this->manager->setCookieLocale($response, $locale);
        }

        return $response;
    }
}
