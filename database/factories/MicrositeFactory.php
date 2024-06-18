<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Microsite>
 */
class MicrositeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->faker->imageUrl,
            'category_id' => Category::factory(),
            'payment_currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'payment_expiration' => $this->faker->numberBetween(1, 365),
            'type' => $this->faker->randomElement(array_column(MicrositeType::cases(), 'value')),
        ];
    }
}
