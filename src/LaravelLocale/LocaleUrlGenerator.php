<?php

namespace KiaKing\LaravelLocale;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;

class LocaleUrlGenerator
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
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     */
    function __construct(Config $config, Request $request, UrlGenerator $url)
    {
        $this->config = $config;
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * Generate a absolute URL to the given path.
     *
     * @param  string $path
     * @param  mixed  $extra
     * @param  bool   $secure
     * @return string
     */
    public function url($path, $extra = [], $secure = null)
    {
        if ($this->isCurrentLocaleDefault()) {
            return $this->url->to($path, $extra, $secure);
        }

        $path = $this->config->get('app.locale') . '/' . $path;

        return $this->url->to($path, $extra, $secure);
    }

    /**
     * Get the URL to a named route.
     *
     * @param  string $name
     * @param  mixed  $parameters
     * @param  bool   $absolute
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
     * Get the URL to a different locale.
     *
     * @param  string $locale
     * @return string
     */
    public function change($locale)
    {
        if ($locale == $this->config->get('app.locale')) {
            return $this->request->fullUrl();
        }

        if ($locale == $this->config->get('app.fallback_locale')) {
            return $this->replaceLocaleString('/');
        }

        if ( ! $this->isCurrentLocaleDefault()) {
            return $this->replaceLocaleString("/{$locale}/");
        }

        $root = $this->request->root();

        return str_replace($root, "{$root}/{$locale}", $this->request->fullUrl());
    }

    /**
     * Replace locale string from uri.
     *
     * @param  string $replace
     * @return string
     */
    protected function replaceLocaleString($replace)
    {
        $preg = '';
        $count = 0;

        foreach ($this->config->get('locale.available_locales') as $key => $available) {
            if ($available != $this->config->get('app.fallback_locale')) {
                $preg = $count > 0 ? $preg.'|'.$available : $available;
                $count++;
            }
        }

        return preg_replace('/\/('.$preg.')($|\/)/', $replace, $this->request->fullUrl(), 1);
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
