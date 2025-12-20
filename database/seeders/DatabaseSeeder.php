<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create an Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@inventory.com',
            'password' => bcrypt('Ademola&&'), // Default password
        ]);

        // 2. Create some Categories
        \App\Models\Category::create(['name' => 'Electronics', 'description' => 'Gadgets and devices']);
        \App\Models\Category::create(['name' => 'Groceries', 'description' => 'Daily essentials']);
        \App\Models\Category::create(['name' => 'Clothing', 'description' => 'Men and Women fashion']);

        // 3. Create a Supplier
        \App\Models\Supplier::create(['name' => 'Global Tech Supplies', 'phone' => '0240000000']);
    }
}
