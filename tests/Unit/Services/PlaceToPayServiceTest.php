<?php

namespace Tests\Unit\Services;

use App\Constants\PlaceToPayStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PlaceToPayService;
use Exception;
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
            config('placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::OK->value,
                'requestId' => '12345',
                'processUrl' => 'https://example.com/payment'
            ]),
        ]);

        $customer = Customer::factory()->create();

        $payment = Payment::factory()->create();

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
            config('placetopay.url') => Http::response(['status' => PlaceToPayStatus::APPROVED->value]),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkSession('test_session_id');

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(PlaceToPayStatus::APPROVED->value, $response['data']['status']);
    }

    public function test_can_create_a_subscription(): void
    {
        Http::fake([
            config('placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::OK->value,
                'requestId' => '12345',
                'processUrl' => 'https://example.com/subscription'
            ]),
        ]);

        $customer = Customer::factory()->create();

        $subscription = Subscription::factory()->create();

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
            config('placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::APPROVED->value
            ]),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkSession('test_session_id');

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(PlaceToPayStatus::APPROVED->value, $response['data']['status']);
    }

    public function test_can_cancel_a_subscription(): void
    {
        Http::fake([
            config('placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::OK->value
            ]),
        ]);

        $service = new PlaceToPayService();

        $response = $service->cancelSubscription(encrypt('test_subscription_token'));

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(PlaceToPayStatus::OK->value, $response['data']['status']);
    }

    public function test_can_collect_a_subscription(): void
    {
        Http::fake([
            config('placetopay.url') => Http::response([
                'status' => PlaceToPayStatus::APPROVED->value,
                'requestId' => '12345',
                'processUrl' => 'https://example.com/subscription'
            ]),
        ]);

        $customer = Customer::factory()->create();
        $subscription = Subscription::factory()->create();
        $payment = Payment::factory()->create();

        $service = new PlaceToPayService();

        $response = $service->collectSubscriptionPayment($customer, $subscription, $payment);

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('requestId', $response['data']);
        $this->assertArrayHasKey('processUrl', $response['data']);
        $this->assertEquals(PlaceToPayStatus::APPROVED->value, $response['data']['status']);
    }

    public function test_handle_http_request_with_unsuccessful_response()
    {
        Http::fake([
            config('placetopay.url') => Http::response([], 400),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkSession('test_session_id');

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals(__('placetopay.request_failed'), $response['message']);
    }

    public function test_handle_http_request_with_exception()
    {
        Http::fake([
            config('placetopay.url') => Http::throw(function () {
                return new Exception('Error occurred');
            }),
        ]);

        $service = new PlaceToPayService();

        $response = $service->checkSession('test_session_id');

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals(__('placetopay.error_occurred'), $response['message']);
    }
}
