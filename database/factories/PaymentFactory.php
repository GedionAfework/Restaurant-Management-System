<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
        'amount_paid' => $faker->randomFloat(2, 10, 100),
        'payment_method' => $faker->randomElement(['credit_card', 'cash', 'paypal']),
        'payment_time' => $faker->dateTime(),
        ];
    }
}
