<?php

namespace Database\Factories;

use App\Constants\BillingUnit;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{

    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'microsite_id' => Microsite::factory(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'total_duration' => $this->faker->numberBetween(1, 12),
            'billing_frequency' => $this->faker->numberBetween(3, 12),
            'billing_unit' => $this->faker->randomElement(BillingUnit::toArray()),
        ];
    }
}
