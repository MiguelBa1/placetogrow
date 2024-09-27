<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\SubscriptionStatus;
use App\Constants\TimeUnit;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durations = [3, 6, 12];
        $billingFrequencies = [1, 2, 3, 6];

        $totalDuration = $this->faker->randomElement($durations);

        $validBillingFrequencies = array_filter($billingFrequencies, fn ($frequency) => $totalDuration % $frequency === 0);

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
            'token' => encrypt(Str::random(40)),
            'subtoken' => encrypt(Str::random(40)),
            'additional_data' => $this->faker->randomElements(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'billing_frequency' => $this->faker->randomElement($validBillingFrequencies),
            'time_unit' => $this->faker->randomElement(array_column(TimeUnit::cases(), 'value')),
        ];
    }
}
