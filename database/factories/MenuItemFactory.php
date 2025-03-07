<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $faker->word(),
        'description' => $faker->sentence(),
        'price' => $faker->randomFloat(2, 5, 50),
        'category_id' => \App\Models\MenuCategory::factory(),
        ];
    }
}
