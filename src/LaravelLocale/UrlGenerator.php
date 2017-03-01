<?php

namespace KiaKing\LaravelLocale;

use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\UrlGenerator as LaravelUrlGenerator;

class UrlGenerator
{
    /**
     * Instance of config.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Instance of request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Instance of url generator.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * Create LocaleUrlGenerator instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Routing\UrlGenerator  $url
     * @return void
     */
    function __construct(Config $config, Request $request, LaravelUrlGenerator $url)
    {
        $this->config = $config;
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * Generate a absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
     * @param  bool  $secure
     * @return string
     */
    public function url($path, $extra = [], $secure = null)
    {
        // Do not modify path if it starts with `http`.
        if (preg_match('/^http/', $path)) {
            return $this->url->to($path, $extra, $secure);
        }

        if ($this->isCurrentLocaleDefault()) {
            return $this->url->to($path, $extra, $secure);
        }

        $path = $this->config->get('app.locale').'/'.$path;

        return $this->url->to($path, $extra, $secure);
    }

    /**
     * Get the URL to a named route.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        if ($this->isCurrentLocaleDefault()) {
            return $this->url->route($name, $parameters, $absolute);
        }

        $name = $this->config->get('app.locale') . '.' . $name;

        return $this->url->route($name, $parameters, $absolute);
    }

    /**
     * Generate URL suffixed with switch_locale_to.
     *
     * @param  string  $locale
     * @return string
     */
    public function change($locale)
    {
        $current = $this->request->fullUrl();
        $prefix = strpos($current, '?') ? '&' : '?';

        return $current.$prefix.'switch_locale_to='.$locale;
    }

    /**
     * Get the URL to a different locale.
     *
     * @param  string  $locale
     * @return string
     */
    public function urlFor($locale)
    {
        $uri = $this->getFullUri();
        $firstSegment = $this->request->segment(1);

        if ( ! in_array($firstSegment, $this->config->get('locale.available_locales'))) {
            if ($locale == $this->config->get('app.fallback_locale')) {
                return $uri;
            }

            $root = $this->request->root();

            return str_replace($root, "{$root}/{$locale}", $uri);
        }

        $replace = $locale == $this->config->get('app.fallback_locale') ? '' : "/{$locale}";

        return preg_replace('/\/('.$firstSegment.')/', $replace, $uri, 1);
    }

    /**
     * Get full uri but remove switch_locale_to if it's present.
     *
     * @param  string  $uri
     * @return string
     */
    protected function getFullUri()
    {
        if ( ! $this->request->switch_locale_to) {
            return $this->request->fullUrl();
        }

        return preg_replace('/(\?|&)switch_locale_to=.+?(?:(?!&).)/', '', $this->request->fullUrl());
    }

    /**
     * Check if current locale is default locale or not.
     *
     * @return bool
     */
    protected function isCurrentLocaleDefault()
    {
        return ($this->config->get('app.locale') == $this->config->get('app.fallback_locale'));
    }
}
