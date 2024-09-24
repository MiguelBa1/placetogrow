<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\SubscriptionStatus;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CustomerSubscription>
 */
class CustomerSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'plan_id' => Plan::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => SubscriptionStatus::PENDING->value,
            'reference' => Str::uuid(),
            'description' => $this->faker->sentence(),
            'request_id' => Str::uuid(),
            'status_message' => $this->faker->sentence(),
            'currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'token' => Str::random(40),
            'subtoken' => Str::random(40),
            'additional_data' => $this->faker->randomElements(),
        ];
    }
}
