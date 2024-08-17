<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\SubscriptionTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubscriptionTranslation>
 */
class SubscriptionTranslationFactory extends Factory
{
    protected $model = SubscriptionTranslation::class;

    public function definition(): array
    {
        $planKey = $this->faker->randomElement(['basic', 'medium', 'premium']);
        $locale = $this->faker->randomElement(['en', 'es']);

        $name = __('subscription.plans.' . $planKey . '.name', [], $locale);
        $description = __('subscription.plans.' . $planKey . '.description', [], $locale);

        return [
            'subscription_id' => Subscription::factory(),
            'locale' => $locale,
            'name' => $name,
            'description' => $description,
        ];
    }
}
