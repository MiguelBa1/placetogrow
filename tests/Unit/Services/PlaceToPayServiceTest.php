<?php

namespace Tests\Unit\Services;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\PaymentStatus;
use App\Constants\PlaceToPayStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PlaceToPayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\PlaceToPayMockTrait;

class PlaceToPayServiceTest extends TestCase
{
    use RefreshDatabase, PlaceToPayMockTrait;

    public function test_can_create_a_payment(): void
    {
        Http::fake([
            config('payments.placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::OK->value,
                'requestId' => '12345',
                'processUrl' => 'https://example.com/payment'
            ]),
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

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('requestId', $response['data']);
        $this->assertArrayHasKey('processUrl', $response['data']);
        $this->assertEquals(PlaceToPayStatus::OK->value, $response['data']['status']);
    }

    public function test_can_check_a_payment(): void
    {
        Http::fake([
            config('payments.placetopay.url') => Http::response(['status' => PaymentStatus::APPROVED->value]),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkSession('test_session_id');

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(PaymentStatus::APPROVED->value, $response['data']['status']);
    }

    public function test_can_create_a_subscription(): void
    {
        Http::fake([
            config('payments.placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::OK->value,
                'requestId' => '12345',
                'processUrl' => 'https://example.com/subscription'
            ]),
        ]);

        $customer = Customer::factory()->create([
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@mail.com',
            'document_type' => DocumentType::CC->value,
            'document_number' => '1234567890',
            'phone' => '1234567890',
        ]);

        $subscription = Subscription::factory()->create([
            'reference' => 'test_reference',
            'description' => 'Test Subscription',
        ]);

        $service = new PlaceToPayService();

        $response = $service->createSubscription($customer, $subscription);

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('requestId', $response['data']);
        $this->assertArrayHasKey('processUrl', $response['data']);
        $this->assertEquals(PlaceToPayStatus::OK->value, $response['data']['status']);
    }

    public function test_can_check_a_subscription(): void
    {
        Http::fake([
            config('payments.placetopay.url') => Http::response([
                'status' => PaymentStatus::APPROVED->value
            ]),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkSession('test_session_id');

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(PaymentStatus::APPROVED->value, $response['data']['status']);
    }
}
