<?php

namespace Database\Factories;

use App\Constants\FieldType;
use App\Models\MicrositeField;
use Illuminate\Database\Eloquent\Factories\Factory;

class MicrositeFieldFactory extends Factory
{
    protected $model = MicrositeField::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'label' => $this->faker->sentence,
            'type' => $this->faker->randomElement(FieldType::toArray()),
            'validation_rules' => 'required|string|max:255',
            'options' => $this->faker->randomElement([null, ['Option 1', 'Option 2']]),
        ];
    }
}
