<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Microsite;
use App\Constants\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Constants\MicrositeType;


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
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'expiration_date' => Carbon::now()->addDays($paymentExpiration),
        ];
    }
}
