<?php

namespace Tests\Feature\Controllers\Subscription;

use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Constants\TimeUnit;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private Microsite $microsite;

    private Microsite $subscriptionMicrosite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();

        $adminUser = User::factory()->create()->assignRole(Role::ADMIN);

        $this->microsite = Microsite::factory()->create(['type' => MicrositeType::SUBSCRIPTION]);

        $this->actingAs($adminUser);
    }

    public function test_admin_can_view_subscriptions_index()
    {

        Plan::factory()->count(3)->create(['microsite_id' => $this->microsite->id]);

        $response = $this->get(route('microsites.subscriptions.index', $this->microsite));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Subscriptions/Index')
            ->has('subscriptions.data', 3));
    }

    public function test_admin_can_view_create_subscription_page()
    {
        $response = $this->get(route('microsites.subscriptions.create', $this->microsite));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Subscriptions/Create')
            ->has('microsite')
            ->has('timeUnits'));
    }

    public function test_admin_can_view_edit_subscription_page()
    {
        $subscription = Plan::factory()->create(['microsite_id' => $this->microsite->id]);

        $response = $this->get(route('microsites.subscriptions.edit', [$this->microsite, $subscription]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Subscriptions/Edit')
            ->has('subscription')
            ->has('microsite')
            ->has('timeUnits'));
    }

    public function test_admin_can_create_subscription()
    {

        $response = $this->post(route('microsites.subscriptions.store', $this->microsite), [
            'price' => 1500,
            'total_duration' => 12,
            'billing_frequency' => 1,
            'time_unit' => TimeUnit::MONTHS->value,
            'translations' => [
                ['locale' => 'en', 'name' => 'Basic Plan', 'description' => 'Basic plan description'],
                ['locale' => 'es', 'name' => 'Plan Básico', 'description' => 'Descripción del plan básico'],
            ],
        ]);

        $response->assertRedirect(route('microsites.subscriptions.index', $this->microsite));

        $this->assertDatabaseHas('subscriptions', [
            'microsite_id' => $this->microsite->id,
            'price' => 1500,
            'total_duration' => 12,
        ]);
        $this->assertDatabaseHas('subscription_translations', [
            'locale' => 'en',
            'name' => 'Basic Plan',
        ]);
        $this->assertDatabaseHas('subscription_translations', [
            'locale' => 'es',
            'name' => 'Plan Básico',
        ]);
    }

    public function test_admin_can_update_subscription()
    {
        $subscription = Plan::factory()->create(['microsite_id' => $this->microsite->id]);

        $response = $this->put(route('microsites.subscriptions.update', [$this->microsite, $subscription]), [
            'price' => 2000,
            'total_duration' => 6,
            'billing_frequency' => 2,
            'time_unit' => TimeUnit::MONTHS->value,
            'translations' => [
                ['locale' => 'en', 'name' => 'Updated Plan', 'description' => 'Updated plan description'],
                ['locale' => 'es', 'name' => 'Plan Actualizado', 'description' => 'Descripción del plan actualizado'],
            ],
        ]);

        $response->assertRedirect(route('microsites.subscriptions.index', $this->microsite));
        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'price' => 2000,
            'total_duration' => 6,
        ]);
        $this->assertDatabaseHas('subscription_translations', [
            'subscription_id' => $subscription->id,
            'locale' => 'en',
            'name' => 'Updated Plan',
        ]);
        $this->assertDatabaseHas('subscription_translations', [
            'subscription_id' => $subscription->id,
            'locale' => 'es',
            'name' => 'Plan Actualizado',
        ]);
    }

    public function test_admin_can_delete_subscription()
    {
        $subscription = Plan::factory()->create(['microsite_id' => $this->microsite->id]);

        $response = $this->delete(route('microsites.subscriptions.destroy', [$this->microsite, $subscription]));

        $response->assertRedirect(route('microsites.subscriptions.index', $this->microsite));

        // This model implements soft deletes

        $this->assertSoftDeleted('subscriptions', ['id' => $subscription->id]);
    }

    public function test_admin_can_restore_subscription()
    {
        $subscription = Plan::factory()->create([
            'microsite_id' => $this->microsite->id,
            'deleted_at' => now(),
        ]);

        $response = $this->put(route('microsites.subscriptions.restore', [$this->microsite, $subscription]));

        $response->assertRedirect(route('microsites.subscriptions.index', $this->microsite));

        $this->assertDatabaseHas('subscriptions', ['id' => $subscription->id]);
    }
}
