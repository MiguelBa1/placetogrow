<?php

namespace Tests\Feature\Controllers\Dashboard;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Constants\Role;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
        $this->customerUser = User::factory()->create()->assignRole(Role::CUSTOMER);
    }

    public function test_dashboard_returns_expected_data(): void
    {
        $this->withoutExceptionHandling();
        $this->seedTestData();

        $response = $this->actingAs($this->adminUser)->get(route('dashboard'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn ($page) => $page
                ->component('Dashboard/Index')
                ->has('data.paymentsOverTime')
                ->has('data.topMicrositesByTransactions')
                ->has('data.invoiceDistribution')
                ->has('data.subscriptionDistribution')
                ->has('data.approvedTransactionsByMicrositeType')
                ->has('lastUpdated')
        );
    }

    private function seedTestData(): void
    {
        $basicMicrosite = Microsite::factory()->create(['type' => MicrositeType::BASIC->value]);
        $invoiceMicrosite = Microsite::factory()->create(['type' => MicrositeType::INVOICE->value]);
        $subscriptionMicrosite = Microsite::factory()->create(['type' => MicrositeType::SUBSCRIPTION->value]);

        Payment::factory(3)->create([
            'microsite_id' => $basicMicrosite->id,
            'status' => PaymentStatus::APPROVED->value,
            'currency' => CurrencyType::USD->value,
            'payment_date' => Carbon::now(),
        ]);

        Payment::factory(3)->create([
            'microsite_id' => $invoiceMicrosite->id,
            'status' => PaymentStatus::APPROVED->value,
            'currency' => CurrencyType::COP->value,
            'payment_date' => Carbon::now(),
        ]);

        Invoice::factory(3)->create([
            'microsite_id' => $invoiceMicrosite->id,
            'status' => PaymentStatus::PENDING->value,
        ]);

        $plans = Plan::factory(2)->create([
            'microsite_id' => $subscriptionMicrosite->id,
        ]);

        Subscription::factory(3)->create([
            'plan_id' => $plans->random()->id,
        ]);
    }
}
