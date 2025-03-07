<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->unique()->safeEmail(),
        'phone_number' => $faker->phoneNumber(),
        'role' => $faker->randomElement(['admin', 'waiter', 'chef', 'manager']),
        'salary' => $faker->randomFloat(2, 2000, 5000), // Random salary
        'password_hash' => bcrypt('password'),
        'work_schedule' => $faker->text(),
        ];
    }
}
