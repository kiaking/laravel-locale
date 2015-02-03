<?php namespace KiaKing\LaravelLocale;

use Illuminate\Support\ServiceProvider;
use KiaKing\LaravelLocale\LocaleRouter;
use KiaKing\LaravelLocale\LocaleUrlGenerator;

class LocaleServiceProvider extends ServiceProvider {

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerLocaleRouter();
		$this->registerLocaleUrlGenerator();
	}

	/**
	 * Register LocaleRouter.
	 *
	 * @return void
	 */
	protected function registerLocaleRouter()
	{
		$this->app->bind('locale', function ($app)
		{
			return new LocaleRouter($app['config'], $app['router']);
		});
	}

	/**
	 * Register LocaleUrlGenerator.
	 *
	 * @return void
	 */
	protected function registerLocaleUrlGenerator()
	{
		$this->app->bind('locale.url', function ($app)
		{
			return new LocaleUrlGenerator($app['app'], $app['config'], $app['request'], $app['url']);
		});
	}

}
