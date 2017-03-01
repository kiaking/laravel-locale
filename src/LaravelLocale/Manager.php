<?php

namespace KiaKing\LaravelLocale;

use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;

class Manager
{
    /**
     * The config implementation.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The request implementation.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new Locale Middleware instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Get default locale for application.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->config->get('app.fallback_locale');
    }

    /**
     * Get list of available locales.
     *
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->config->get('locale.available_locales');
    }

    /**
     * Get present locale within URI. It will return null if no valid
     * locale value exists in URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function getShownLocaleInUri(Request $request)
    {
        $segment = $request->segment(1);

        if ($this->isValidLocale($segment)) {
            return $segment;
        }

        return null;
    }

    /**
     * Get locale for uri.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getUriLocale(Request $request)
    {
        $locale = $this->getShownLocaleInUri($request);

        return $locale ? $locale : $this->getDefaultLocale();
    }

    /**
     * Return requested uri without locale.
     *
     * @return string
     */
    public function getUriWithoutLocale()
    {
        $current = $this->getUriLocale();
        $default = $this->getDefaultLocale();
        $path = $this->request->path();

        if ($current == $default) {
            return $path;
        }

        return preg_replace('/'.$current.'\/?/', '', $path);
    }

    /**
     * Get user's locale.
     *
     * @return string
     */
    public function getUserLocale()
    {
        if ($this->getCookieLocale()) {
            return $this->getCookieLocale();
        }

        if ($this->isValidLocale($this->getBrowserLocale())) {
            return $this->getBrowserLocale();
        }

        return $this->getDefaultLocale();
    }

    /**
     * Get browser locale.
     *
     * @return string
     */
    public function getBrowserLocale()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        return $this->getUriLocale();
    }

    /**
     * Get locale stored at cookie.
     *
     * @return mixed
     */
    public function getCookieLocale()
    {
        return $this->request->cookie('locale');
    }

    /**
     * Set application locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale($locale)
    {
        $this->config->set('app.locale', $locale);
    }

    /**
     * Set locale to cookie.
     *
     * @param  mixed  $response
     * @param  string  $locale
     * @return \Illuminate\Http\Response
     */
    public function setCookieLocale($response, $locale)
    {
        return $response->withCookie('locale', $locale, 525600);
    }

    /**
     * Check if the given locale is available.
     *
     * @param  string  $locale
     * @return boolean
     */
    public function isValidLocale($locale)
    {
        return in_array($locale, $this->getAvailableLocales());
    }

    /**
     * Get switch request locale.
     *
     * @return mixed
     */
    public function getSwitchRequestLocale()
    {
        $locale = $this->localeSwitchRequest();

        return $this->isValidLocale($locale) ? $locale : $this->getUserLocale();
    }

    /**
     * Get locale switch request.
     *
     * @return mixed
     */
    public function localeSwitchRequest()
    {
        return $this->request->switch_locale_to;
    }

    /**
     * Determine if the access is from web crawler.
     *
     * @return bool
     */
    public function isWebCrawler()
    {
        if ( ! isset($_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (preg_match('/google|yahoo|bing/', $userAgent) > 0) {
            return true;
        }

        return false;
    }
}
