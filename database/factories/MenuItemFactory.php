<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MenuItem;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['Breakfast', 'Main Course', 'Dessert', 'Drink']),
            'description' => $this->faker->sentence,
            'picture' => null,
            'price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}