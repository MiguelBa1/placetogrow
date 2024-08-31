<?php

namespace Database\Factories;

use App\Constants\ImportStatus;
use App\Models\Import;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportFactory extends Factory
{
    protected $model = Import::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => $this->faker->word,
            'status' => $this->faker->randomElement(array_column(ImportStatus::cases(), 'value')),
            'errors' => [],
        ];
    }

}
