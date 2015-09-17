<?php

if ( ! function_exists('lurl'))
{
    /**
     * Generate a absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
     * @param  bool  $secure
     * @return string
     */
    function lurl($path, $extra = array(), $secure = null)
    {
        return app('locale.url')->url($path, $extra, $secure);
    }
}

if ( ! function_exists('lroute'))
{
    /**
     * Get the URL to a named route.
     *
     * @param  string $name
     * @param  mixed  $parameters
     * @param  bool   $absolute
     * @return string
     */
    function lroute($name, $parameters = array(), $absolute = true)
    {
        return app('locale.url')->route($name, $parameters, $absolute);
    }
}

if ( ! function_exists('lchange'))
{
    /**
     * Get the URL to a different locale.
     *
     * @param  string $locale
     * @return string
     */
    function lchange($locale)
    {
        return app('locale.url')->change($locale);
    }
}
