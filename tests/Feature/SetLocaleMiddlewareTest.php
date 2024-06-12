<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SetLocaleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sets_the_locale_from_session(): void
    {
        Session::put('locale', 'es');

        $response = $this->get('/');

        $response->assertOk();
        $this->assertEquals('es', App::getLocale());
    }

    public function test_it_falls_back_to_default_locale_if_session_locale_is_invalid(): void
    {
        Session::put('locale', 'fr');

        $response = $this->get('/');

        $response->assertOk();
        $this->assertEquals(config('app.locale'), App::getLocale());
    }
}
