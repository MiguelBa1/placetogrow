<?php

namespace Tests\Feature\Controllers\Microsite;

use App\Constants\LateFeeType;
use App\Constants\MicrositeType;
use App\Constants\Role;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class UpdateMicrositeSettingsTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_update_settings_for_invoice_microsite()
    {
        $microsite = Microsite::factory()->create(['type' => MicrositeType::INVOICE]);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.update.settings', $microsite), [
            'settings' => [
                'late_fee' => [
                    'type' => LateFeeType::PERCENTAGE->value,
                    'value' => 15.5,
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('microsites', [
            'id' => $microsite->id,
            'settings->late_fee->type' => LateFeeType::PERCENTAGE->value,
            'settings->late_fee->value' => 15.5,
        ]);
    }

    public function test_admin_can_update_settings_for_subscription_microsite()
    {
        $microsite = Microsite::factory()->create(['type' => MicrositeType::SUBSCRIPTION]);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.update.settings', $microsite), [
            'settings' => [
                'retry' => [
                    'max_retries' => 3,
                    'retry_backoff' => 24,
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('microsites', [
            'id' => $microsite->id,
            'settings->retry->max_retries' => 3,
            'settings->retry->retry_backoff' => 24,
        ]);
    }

    public function test_admin_cannot_update_settings_with_invalid_data()
    {
        $microsite = Microsite::factory()->create(['type' => MicrositeType::SUBSCRIPTION]);

        $response = $this->actingAs($this->adminUser)->post(route('microsites.update.settings', $microsite), [
            'settings' => [
                'retry' => [
                    'max_retries' => -1,
                    'retry_backoff' => 0,
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['settings.retry.max_retries', 'settings.retry.retry_backoff']);
    }
}
