<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available Locales Configuration
    |--------------------------------------------------------------------------
    |
    | The available locales determine what languages can be used in the
    | application. Basically, the routes will be created for each of
    | the languages you specify here.
    |
    | For example, if you specify "ja" and "en", following routes
    | will be generated.
    |
    | - https://example.com/posts
    | - https://example.com/en/posts
    | - https://example.com/ja/posts
    |
    | Remember that the locale specified at "app.fallback_locale" will be used
    | as default locale. Which means if "app.fallback_locale" is set to "en",
    | access to `https://example.com/posts` will set locale to "en". Also
    | access to `https://example.com/en/posts` will be redirected to
    | `https://example.com/posts`.
    |
    */

    'available_locales' => ['en', 'ja'],

];
