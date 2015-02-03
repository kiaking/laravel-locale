<?php namespace KiaKing\LaravelLocale;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;

class LocaleUrlGenerator {

	/**
	 * Instance of Application.
	 *
	 * @var Application
	 */
	protected $app;

	/**
	 * Instance of Application.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Instance of Request.
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * Instance of UrlGenerator.
	 *
	 * @var UrlGenerator
	 */
	protected $url;

	/**
	 * Create LocaleUrlGenerator instance.
	 *
	 * @param Application  $app
	 * @param Config       $config
	 * @param Request      $request
	 * @param UrlGenerator $url
	 */
	function __construct(Application $app, Config $config, Request $request, UrlGenerator $url)
	{
		$this->app = $app;
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
	public function url($path, $extra = array(), $secure = null)
	{
		if ($this->isCurrentLocaleDefault())
		{
			return $this->url->to($path, $extra, $secure);
		}

		$path = $this->app->getLocale() . '/' . $path;

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
	public function route($name, $parameters = array(), $absolute = true)
	{
		if ($this->isCurrentLocaleDefault())
		{
			return $this->url->route($name, $parameters, $absolute);
		}

		$name = $this->app->getLocale() . '.' . $name;

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
		if ($locale == $this->app->getLocale())
		{
			return $this->request->fullUrl();
		}

		$preg = '/\/.{2}($|\/)/';

		if ($locale == $this->config->get('app.fallback_locale'))
		{
			return preg_replace($preg, '/', $this->request->fullUrl());
		}

		if ( ! $this->isCurrentLocaleDefault())
		{
			return preg_replace($preg, "/{$locale}/", $this->request->fullUrl());
		}

		$root = $this->request->root();

		return str_replace($root, "{$root}/{$locale}", $this->request->fullUrl());
	}

	/**
	 * Check if current locale is default locale or not.
	 *
	 * @return bool
	 */
	protected function isCurrentLocaleDefault()
	{
		return ($this->app->getLocale() == $this->config->get('app.fallback_locale'));
	}

}
