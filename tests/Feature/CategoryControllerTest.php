<?php

namespace Tests\Feature;

use App\Constants\Role;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $guestUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
        $this->guestUser = User::factory()->create()->assignRole(Role::GUEST);
    }

    public function test_admin_can_view_categories_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('categories.index'));

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Categories/Index')
            ->has('categories')
        );
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->adminUser)->post(route('categories.store'), [
            'name' => 'Test Category',
            'logo' => 'https://example.com/logo.png',
        ]);

        $response->assertFound();
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->adminUser)->put(route('categories.update', $category), [
            'name' => 'Updated Category',
            'logo' => 'https://example.com/new-logo.png',
        ]);

        $response->assertFound();
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('categories.destroy', $category));

        $response->assertFound();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_guest_cannot_create_category()
    {
        $response = $this->actingAs($this->guestUser)->post(route('categories.store'), [
            'name' => 'Test Category',
            'logo' => 'https://example.com/logo.png',
        ]);

        $response->assertForbidden();
    }

}
