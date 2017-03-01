<?php

namespace KiaKing\LaravelLocale\Middleware;

use Closure;
use KiaKing\LaravelLocale\Manager;
use KiaKing\LaravelLocale\UrlGenerator as Url;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory as Response;

class SetLocale
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
     * @var \KiaKing\LaravelLocale\UrlGenerator
     */
    protected $url;

    /**
     * Create a new Locale Middleware instance.
     *
     * @param  \KiaKing\LaravelLocale\Manager  $manager
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param  \KiaKing\LaravelLocale\UrlGenerator  $url
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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If user requested to change locale, redirect them to proper page.
        if ($this->manager->localeSwitchRequest()) {
            $locale = $this->manager->getSwitchRequestLocale();

            $response = $this->response->redirectTo(
                $this->url->urlFor($locale)
            );

            return $this->manager->setCookieLocale($response, $locale);
        }

        // If the shown locale value within URI is same as default locale,
        // then redirect them.
        if ($this->manager->getShownLocaleInUri($request) == $this->manager->getDefaultLocale()) {
            return $this->response->redirectTo(
                $this->url->urlFor($this->manager->getDefaultLocale())
            );
        }

        $this->manager->setLocale($this->manager->getUriLocale($request));

        return $next($request);
    }
}
