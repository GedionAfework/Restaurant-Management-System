<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_id' => \App\Models\Inventory::factory(),
        'transaction_type' => $faker->randomElement(['in', 'out']),
        'quantity' => $faker->numberBetween(1, 50),
        'transaction_time' => $faker->dateTime(),
        ];
    }
}
