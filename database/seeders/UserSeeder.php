<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test seller user
        User::factory()->create([
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
        ]);

        // Create a test buyer user
        User::factory()->create([
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
        ]);

        // Create additional random users
        User::factory(5)->create();
    }
}
