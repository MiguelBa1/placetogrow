<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return Factory
     */
    public function configure(): Factory
    {
        return $this->afterCreating(function (Category $category) {
            $svgFiles = [
                'category-icons/ecommerce.svg',
                'category-icons/saas.svg',
                'category-icons/fintech.svg',
                'category-icons/nonprofit.svg',
                'category-icons/education.svg',
            ];

            $randomSvg = $this->faker->randomElement($svgFiles);
            $svgPath = Storage::disk('test_images')->path($randomSvg);

            $category->addMedia($svgPath)
                ->preservingOriginal()
                ->toMediaCollection('logos');
        });
    }
}
