<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        Category::factory()->count(5)->create();
        Microsite::factory()->count(15)->create();
    }

    public function test_home_page_loads_correctly()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $page) {
            $page->component('Home/Index')
                ->has('categories')
                ->has('microsites');
        });
    }

    public function test_home_page_with_category_filter()
    {
        $category = Category::first();

        $response = $this->get(route('home', ['category' => $category->id]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $page) use ($category) {
            $page->where('filters.category', (string) $category->id)
                ->has('microsites');
        });
    }

    public function test_home_page_with_search_filter()
    {
        $microsite = Microsite::first();
        $searchTerm = substr($microsite->name, 0, 5);

        $response = $this->get(route('home', ['search' => $searchTerm]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $page) use ($searchTerm) {
            $page->where('filters.search', $searchTerm)
                ->has('microsites');
        });
    }
}
