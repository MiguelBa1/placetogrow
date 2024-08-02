<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\PaymentStatus;
use App\Models\Guest;
use App\Models\Microsite;
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
            'microsite_id' => Microsite::factory(),
            'reference' => $this->faker->unique()->word,
            'request_id' => Str::random(),
            'payment_method_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'authorization' => Str::random(),
            'status' => PaymentStatus::PENDING->value,
            'status_message' => $this->faker->sentence,
            'payment_date' => $this->faker->dateTimeBetween('-1 month'),
            'currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'amount' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
