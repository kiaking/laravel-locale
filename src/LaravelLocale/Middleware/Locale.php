<?php namespace KiaKing\LaravelLocale\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\Middleware;

class Locale implements Middleware {

	/**
	 * The config implementation.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Create a new Locale Middleware instance.
	 *
	 * @param  Config $config
	 * @return void
	 */
	public function __construct(Config $config)
	{
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
		$default    = $this->config->get('app.locale');
		$localeList = $this->config->get('locale.available_locales');

		if (in_array($locale, $localeList) && ($locale != $default))
		{
			$this->config->set('app.locale', $locale);
		}

		return $next($request);
	}

}
