<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = 'Microsite ' . $this->faker->unique()->randomNumber(5);

        return [
            'name' => $name,
            'logo' => $this->faker->imageUrl,
            'category_id' => Category::factory(),
            'payment_currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'payment_expiration' => $this->faker->numberBetween(1, 365),
            'type' => $this->faker->randomElement(array_column(MicrositeType::cases(), 'value')),
            'slug' => Str::slug($name . '-' . $this->faker->unique()->randomNumber(5)),
            'responsible_name' => $this->faker->name,
            'responsible_document_number' => $this->faker->unique()->numerify('##########'),
            'responsible_document_type' => $this->faker->randomElement(array_column(DocumentType::cases(), 'value')),
        ];
    }
}
