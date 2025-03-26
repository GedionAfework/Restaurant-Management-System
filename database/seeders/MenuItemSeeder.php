<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        // Specific items
        MenuItem::create([
            'id' => 1,
            'name' => 'jkdf',
            'type' => 'Main Course',
            'description' => 'A test dish',
            'picture' => null,
            'price' => 25.00,
        ]);

        MenuItem::create([
            'id' => 13,
            'name' => 'genfo',
            'type' => 'Breakfast',
            'description' => 'A delicious porridge',
            'picture' => null,
            'price' => 15.00,
        ]);

        // Optional: Keep factory for additional random items
        MenuItem::factory(18)->create(); // 18 more to make 20 total
    }
}