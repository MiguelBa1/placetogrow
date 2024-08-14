<?php

namespace Database\Factories;

use App\Models\FieldTranslation;
use App\Models\MicrositeField;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldTranslationFactory extends Factory
{
    protected $model = FieldTranslation::class;

    public function definition(): array
    {
        return [
            'field_id' => MicrositeField::factory(),
            'locale' => $this->faker->randomElement(['en', 'es']),
            'label' => $this->faker->sentence,
        ];
    }
}
