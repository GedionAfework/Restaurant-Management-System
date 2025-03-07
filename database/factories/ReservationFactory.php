<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
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
        'reservation_time' => $faker->dateTimeBetween('now', '+2 weeks'),
        'number_of_people' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}
