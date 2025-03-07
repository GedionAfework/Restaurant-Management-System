<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
        'staff_id' => \App\Models\Staff::factory(),
        'total_amount' => $faker->randomFloat(2, 20, 100),
        'status' => $faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}
