<?php

namespace Database\Factories;

use App\Constants\PaymentStatus;
use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guest_id' => Guest::factory(),
            'reference' => Str::random(),
            'request_id' => Str::random(),
            'payment_method_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'authorization' => Str::random(),
            'status' => PaymentStatus::PENDING->value,
            'status_message' => $this->faker->sentence,
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'currency' => $this->faker->randomElement(['USD', 'COP']),
            'amount' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
