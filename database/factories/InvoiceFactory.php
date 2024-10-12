<?php

namespace Database\Factories;

use App\Constants\DocumentType;
use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @method Invoice|Collection<Invoice> create($attributes = [], ?Model $parent = null)
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $type = MicrositeType::INVOICE;
        $paymentExpiration = $type->defaultExpirationDays() ?? $this->faker->numberBetween(1, 365);

        return [
            'microsite_id' => Microsite::factory(),
            'reference' => strtoupper($this->faker->bothify('INV-####??')),
            'document_type' => $this->faker->randomElement(DocumentType::cases())->value,
            'document_number' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(InvoiceStatus::cases())->value,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'expiration_date' => Carbon::now()->addDays($paymentExpiration),
        ];
    }

    public function pending(): self
    {
        return $this->state([
            'status' => InvoiceStatus::PENDING->value,
        ]);
    }

    public function paid(): self
    {
        return $this->state([
            'status' => InvoiceStatus::PAID->value,
        ]);
    }

    public function expired(): self
    {
        return $this->state([
            'status' => InvoiceStatus::EXPIRED->value,
        ]);
    }
}
