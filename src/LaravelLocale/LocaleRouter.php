<?php

namespace KiaKing\LaravelLocale;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\Registrar as Router;

class LocaleRouter
{
    /**
     * Instance of config.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Instance of router.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create Locale instance.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Contracts\Routing\Registrar $router
     */
    function __construct(Config $config, Router $router)
    {
        $this->config = $config;
        $this->router = $router;
    }

    /**
     * Generate GET method routing with locale support.
     *
     * @param  string       $uri
     * @param  string|array $action
     * @return void
     */
    public function get($uri, $action)
    {
        $this->generateRoutes('get', $uri, $action);
    }

    /**
     * Generate POST method routing with locale support.
     *
     * @param  string       $uri
     * @param  string|array $action
     * @return void
     */
    public function post($uri, $action)
    {
        $this->generateRoutes('post', $uri, $action);
    }

    /**
     * Generate PUT method routing with locale support.
     *
     * @param  string       $uri
     * @param  string|array $action
     * @return void
     */
    public function put($uri, $action)
    {
        $this->generateRoutes('put', $uri, $action);
    }

    /**
     * Generate PATCH method routing with locale support.
     *
     * @param  string       $uri
     * @param  string|array $action
     * @return void
     */
    public function patch($uri, $action)
    {
        $this->generateRoutes('patch', $uri, $action);
    }

    /**
     * Generate DELETE method routing with locale support.
     *
     * @param  string       $uri
     * @param  string|array $action
     * @return void
     */
    public function delete($uri, $action)
    {
        $this->generateRoutes('delete', $uri, $action);
    }

    /**
     * Generate routes.
     *
     * @param  string       $method
     * @param  string       $uri
     * @param  string|array $action
     * @return void
     */
    protected function generateRoutes($method, $uri, $action)
    {
        $availables = $this->config->get('locale.available_locales');
        $default = $this->config->get('app.fallback_locale');

        foreach ($availables as $locale) {
            $localedUri = $locale . '/' . $uri;

            if ( ! isset($action['as'])) {
                $this->router->{$method}($localedUri, $action);
                continue;
            }

            $localedAction = array_merge(
                $action, ['as' => $locale . '.' . $action['as']]
            );

            $this->router->{$method}($localedUri, $localedAction);
        }

        $this->router->{$method}($uri, $action);
    }
}
