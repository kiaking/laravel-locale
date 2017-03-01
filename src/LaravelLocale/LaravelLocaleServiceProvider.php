<?php

namespace KiaKing\LaravelLocale;

use Illuminate\Support\ServiceProvider;

class LaravelLocaleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/locale.php' => config_path('locale.php'),
        ]);
    }
}
