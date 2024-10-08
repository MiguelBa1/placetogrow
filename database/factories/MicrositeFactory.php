<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends Factory<Microsite>
 */
class MicrositeFactory extends Factory
{
    protected $model = Microsite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(MicrositeType::cases());
        $typeName = ucfirst($type->value);
        $name = "$typeName " . $this->faker->unique()->randomNumber(5);
        $paymentExpiration = $type->defaultExpirationDays() ?? $this->faker->numberBetween(1, 365);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => Category::factory(),
            'payment_currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'payment_expiration' => $type === MicrositeType::BASIC ? null : $paymentExpiration,
            'type' => $type->value,
            'responsible_name' => $this->faker->name,
            'responsible_document_number' => $this->faker->unique()->numerify('##########'),
            'responsible_document_type' => $this->faker->randomElement(array_column(DocumentType::cases(), 'value')),
        ];
    }

    public function configure(): Factory|MicrositeFactory
    {
        return $this->afterCreating(function (Microsite $microsite) {
            $testImages = Storage::disk('test_images')->files('microsite-logos');

            if (!empty($testImages)) {
                $randomImage = $this->faker->randomElement($testImages);
                $tempPath = Storage::disk('test_images')->path($randomImage);

                $microsite->addMedia($tempPath)
                    ->preservingOriginal()
                    ->toMediaCollection('logos');
            }
        });
    }
}
