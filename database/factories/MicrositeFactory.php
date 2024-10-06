<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\LateFeeType;
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

    public function definition(): array
    {
        $type = $this->faker->randomElement(MicrositeType::cases());
        $typeName = ucfirst($type->value);
        $name = "$typeName " . $this->faker->unique()->randomNumber(5);
        $paymentExpiration = $type === MicrositeType::BASIC ? null : $this->faker->numberBetween(1, 365);

        $settings = $this->generateSettingsForType($type);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => Category::factory(),
            'payment_currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'payment_expiration' => $paymentExpiration,
            'type' => $type->value,
            'responsible_name' => $this->faker->name,
            'responsible_document_number' => $this->faker->unique()->numerify('##########'),
            'responsible_document_type' => $this->faker->randomElement(array_column(DocumentType::cases(), 'value')),
            'settings' => $settings,
        ];
    }

    private function generateSettingsForType(MicrositeType $type): array
    {
        return match ($type) {
            MicrositeType::INVOICE => [
                'late_fee' => [
                    'type' => $this->faker->randomElement(LateFeeType::cases())->value,
                    'value' => $this->faker->randomFloat(2, 1, 100),
                ],
            ],
            MicrositeType::SUBSCRIPTION => [
                'retry' => [
                    'max_retries' => $this->faker->numberBetween(1, 5),
                    'retry_backoff' => $this->faker->numberBetween(1, 48),
                ],
            ],
            default => [],
        };
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
