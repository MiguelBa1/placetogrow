<?php

namespace Tests\Feature\Controllers\Transaction;

use App\Constants\PaymentStatus;
use App\Constants\Role;
use App\Models\Customer;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;
use Tests\Traits\SeedsRolesAndPermissions;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, SeedsRolesAndPermissions;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        $this->adminUser = User::factory()->create()->assignRole(Role::ADMIN);
    }

    public function test_admin_can_view_transactions_index()
    {
        Payment::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)->get(route('transactions.index'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Transactions/Index')
                    ->has('transactions.data', 10)
            );
    }

    public function test_admin_can_view_show_page()
    {
        $transaction = Payment::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('transactions.show', $transaction));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Transactions/Show')
                    ->has('transaction')
            );
    }

    public function test_transactions_index_with_filters()
    {
        $micrositeName = 'Test Microsite';
        $reference = 'TestReference';
        $document = '1234567890';
        $status = PaymentStatus::APPROVED->value;

        $microsite = Microsite::factory()->create(['name' => $micrositeName]);

        $customer = Customer::factory()->create(['document_number' => $document]);

        Payment::factory()->create([
            'reference' => $reference,
            'status' => $status,
            'microsite_id' => $microsite->id,
            'customer_id' => $customer->id,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('transactions.index', [
            'microsite' => $micrositeName,
            'status' => $status,
            'reference' => $reference,
            'document' => $document,
        ]));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Transactions/Index')
                    ->has('transactions.data', 1)
                    ->has(
                        'filters',
                        fn (AssertableJson $json) =>
                    $json->where('microsite', $micrositeName)
                        ->where('status', $status)
                        ->where('reference', $reference)
                        ->where('document', $document)
                    )
            );
    }
}
