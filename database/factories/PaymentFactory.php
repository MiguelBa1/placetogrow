<?php

namespace Database\Factories;

use App\Constants\CurrencyType;
use App\Constants\PaymentStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

/**
 * @extends Factory<Payment>
 * @method Payment|Collection<Payment> create($attributes = [], ?Model $parent = null)
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'microsite_id' => Microsite::factory(),
            'invoice_id' => null,
            'plan_id' => null,
            'reference' => $this->faker->unique()->word,
            'request_id' => Str::random(),
            'payment_method_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'authorization' => Str::random(),
            'status' => $this->faker->randomElement(array_column(PaymentStatus::cases(), 'value')),
            'status_message' => $this->faker->sentence,
            'payment_date' => $this->faker->dateTimeBetween('-1 month'),
            'currency' => $this->faker->randomElement(array_column(CurrencyType::cases(), 'value')),
            'amount' => $this->faker->numberBetween(1000, 100000),
        ];
    }

    public function withInvoice(): self
    {
        return $this->state([
            'invoice_id' => Invoice::factory()->paid(),
            'plan_id' => null,
        ]);
    }

    public function withPlan(): self
    {
        return $this->state([
            'plan_id' => Plan::factory(),
            'invoice_id' => null,
        ]);
    }
}
