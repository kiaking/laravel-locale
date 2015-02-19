<?php namespace KiaKing\LaravelLocale\Facades;

use Illuminate\Support\Facades\Facade;

class LocaleRouter extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'locale'; }

}
