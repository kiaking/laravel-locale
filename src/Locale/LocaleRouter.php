<?php namespace KiaKing\LaravelLocale;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Routing\Router;

class LocaleRouter {

	/**
	 * Instance of Config.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Instance of Router.
	 *
	 * @var Router
	 */
	protected $router;

	/**
	 * Create Locale instance.
	 *
	 * @param Router $router
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
		$availables = $this->config->get('app.available_locales');
		$default = $this->config->get('app.fallback_locale');

		foreach ($availables as $locale)
		{
			if ($locale == $default)
			{
				$this->router->{$method}($uri, $action);
				continue;
			}

			$localedUri = $locale . '/' . $uri;

			if ( ! isset($action['as']))
			{
				$this->router->{$method}($localedUri, $action);
				continue;
			}

			$localedAction = array_merge(
				$action, ['as' => $locale . '.' . $action['as']]
			);

			$this->router->{$method}($localedUri, $localedAction);
		}
	}

}
