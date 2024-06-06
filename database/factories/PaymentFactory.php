<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_reference' => Str::random(),
            'request_id' => Str::random(),
            'process_url' => $this->faker->url,
            'expires_in' => $this->faker->dateTimeBetween('now', '+1 week'),
            'internal_reference' => Str::random(),
            'franchise' => $this->faker->word,
            'payment_method' => $this->faker->creditCardType,
            'payment_method_name' => $this->faker->word,
            'issuer_name' => $this->faker->company,
            'receipt' => Str::random(),
            'authorization' => Str::random(),
            'status' => 'PENDING',
            'status_message' => $this->faker->sentence,
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'currency' => $this->faker->randomElement(['USD', 'COP']),
            'amount' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
