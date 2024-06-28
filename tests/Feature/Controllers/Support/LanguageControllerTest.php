<?php

namespace Tests\Feature\Controllers\Support;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class LanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_the_locale(): void
    {
        $response = $this->post(route('language.update'), ['locale' => 'es']);

        $response->assertRedirect();
        $this->assertEquals('es', Session::get('locale'));
    }

    public function test_it_fails_validation_if_locale_is_invalid(): void
    {
        $response = $this->post(route('language.update'), ['locale' => 'fr']);

        $response->assertSessionHasErrors(['locale']);
        $this->assertNotEquals('fr', Session::get('locale'));
    }

    public function test_it_fails_validation_if_locale_is_missing(): void
    {
        $response = $this->post(route('language.update'), []);

        $response->assertSessionHasErrors(['locale']);
    }
}
