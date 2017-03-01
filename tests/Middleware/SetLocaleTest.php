<?php

namespace Tests\Middleware;

use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(\Illuminate\Contracts\Http\Kernel::class, \Tests\Stubs\Kernel::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.fallback_locale', 'en');
        $app['config']->set('locale.available_locales', ['en', 'ja']);

        $app['router']->middleware('locale')->group(function () use ($app) {
            $app['router']->get('/', function () { return 'hello'; });
            $app['router']->get('ja', function () { return 'hello'; });
            $app['router']->get('en', function () { return 'hello'; });
        });
    }

    /** @test */
    public function it_sets_correct_locale_depending_on_url()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals(\App::getLocale(), 'en');

        $response = $this->get('ja');

        $response->assertStatus(200);
        $this->assertEquals(\App::getLocale(), 'ja');

        $response = $this->get('en');

        $response->assertStatus(302);
        $response->assertRedirect('http://localhost');
    }

    /** @test */
    public function it_redirects_when_locale_switch_was_requested()
    {
        $response = $this->get('ja?switch_locale_to=ja');

        $response->assertStatus(302);
        $response->assertRedirect('http://localhost/ja');
    }
}
