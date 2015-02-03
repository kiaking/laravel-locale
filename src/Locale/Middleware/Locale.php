<?php namespace KiaKing\LaravelLocale\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\Middleware;

class Locale implements Middleware {

	/**
	 * The application implementation.
	 *
	 * @var Application
	 */
	protected $app;

	/**
	 * The config implementation.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Application $app
	 * @return void
	 */
	public function __construct(Application $app, Config $config)
	{
		$this->app = $app;
		$this->config = $config;
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
		$locale     = $request->segment(1);
		$default    = $this->app->getLocale();
		$localeList = $this->config->get('app.available_locales');

		if (in_array($locale, $localeList) && ($locale != $default))
		{
			$this->app->setLocale($locale);
		}

		return $next($request);
	}

}
