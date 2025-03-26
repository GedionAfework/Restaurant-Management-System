<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '1234567890',
            'address' => '123 Main St',
            'preferences' => 'No onions',
            'date_of_birth' => '1990-01-01',
            'password_hash' => bcrypt('password'),
        ]);
    }
}