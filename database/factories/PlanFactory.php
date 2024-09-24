<?php

namespace Database\Factories;

use App\Constants\TimeUnit;
use App\Models\Microsite;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $durations = [3, 6, 12];
        $billingFrequencies = [1, 2, 3, 6];

        $totalDuration = $this->faker->randomElement($durations);

        $validBillingFrequencies = array_filter($billingFrequencies, fn ($frequency) => $totalDuration % $frequency === 0);

        return [
            'microsite_id' => Microsite::factory(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'total_duration' => $totalDuration,
            'billing_frequency' => $this->faker->randomElement($validBillingFrequencies),
            'time_unit' => $this->faker->randomElement(TimeUnit::toArray()),
        ];
    }
}
