<?php

namespace KiaKing\LaravelLocale;

use Closure;
use Illuminate\Contracts\Routing\Registrar as LaravelRouter;

class Router
{
    /**
     * Instance of manager.
     *
     * @var \KiaKing\LaravelLocale\Manager
     */
    protected $manager;

    /**
     * Instance of router.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create Locale instance.
     *
     * @param  \KiaKing\LaravelLocale\Manager  $manager
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    function __construct(Manager $manager, LaravelRouter $router)
    {
        $this->manager = $manager;
        $this->router = $router;
    }

    /**
     * Generate GET method routing with locale support.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */
    public function get($uri, $action)
    {
        $this->generateRoutes('get', $uri, $action);
    }

    /**
     * Generate POST method routing with locale support.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */
    public function post($uri, $action)
    {
        $this->generateRoutes('post', $uri, $action);
    }

    /**
     * Generate PUT method routing with locale support.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */
    public function put($uri, $action)
    {
        $this->generateRoutes('put', $uri, $action);
    }

    /**
     * Generate PATCH method routing with locale support.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */
    public function patch($uri, $action)
    {
        $this->generateRoutes('patch', $uri, $action);
    }

    /**
     * Generate DELETE method routing with locale support.
     *
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */
    public function delete($uri, $action)
    {
        $this->generateRoutes('delete', $uri, $action);
    }

    /**
     * Generate routes.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */
    protected function generateRoutes($method, $uri, $action)
    {
        $this->router->{$method}($uri, $action);

        $availables = $this->manager->getAvailableLocales();
        $default = $this->manager->getDefaultLocale();

        foreach ($availables as $locale) {
            $localedUri = "{$locale}/{$uri}";

            $this->router->{$method}($localedUri, $action);
        }
    }

    /**
     * Create a route group with shared attributes prefixing with locale.
     *
     * @param  array  $attributes
     * @param  \Closure  $callback
     * @return void
     */
    public function group(array $attributes, Closure $callback)
    {
        $this->router->group($attributes, $callback);

        $availables = $this->manager->getAvailableLocales();

        foreach ($availables as $locale) {
            $newAttributes = $attributes;

            $newAttributes['prefix'] = isset($attributes['prefix'])
                                     ? "$locale/{$attributes['prefix']}"
                                     : $locale;

            $this->router->group($newAttributes, $callback);
        }
    }
}
