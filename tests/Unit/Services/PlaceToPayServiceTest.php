<?php

namespace Tests\Unit\Services;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\PaymentStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Services\PlaceToPayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\PlaceToPayMockTrait;

class PlaceToPayServiceTest extends TestCase
{
    use RefreshDatabase, PlaceToPayMockTrait;

    public function test_can_create_a_payment()
    {
        Http::fake([
            config('payments.placetopay.url') => Http::response(['status' => PaymentStatus::OK->value]),
        ]);

        $customer = Customer::factory()->create([
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'document_type' => DocumentType::CC->value,
            'document_number' => '1234567890',
            'phone' => '1234567890',
        ]);

        $payment = Payment::factory()->create([
            'reference' => 'test_reference',
            'description' => 'Test Payment',
            'currency' => CurrencyType::COP->value,
            'amount' => 100.00,
        ]);

        $service = new PlaceToPayService();

        $response = $service->createPayment($customer, $payment);

        $this->assertEquals(200, $response->status());

        $this->assertArrayHasKey('status', $response->json());
        $this->assertEquals('OK', $response->json()['status']['status']);
    }

    public function test_can_check_a_payment()
    {
        Http::fake([
            config('payments.placetopay.url').'/*' => Http::response(['status' => ['status' => PaymentStatus::APPROVED->value]]),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkPayment('test_session_id');

        $this->assertEquals(200, $response->status());

        $this->assertArrayHasKey('status', $response->json());
        $this->assertEquals(PaymentStatus::APPROVED->value, $response->json()['status']['status']);
    }
}
