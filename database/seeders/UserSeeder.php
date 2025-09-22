<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Create a test seller user
        User::updateOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Test Seller',
                'password' => bcrypt('password'),
                'role' => 'seller',
            ]
        );

        // Create a test buyer user
        User::updateOrCreate(
            ['email' => 'buyer@example.com'],
            [
                'name' => 'Test Buyer',
                'password' => bcrypt('password'),
                'role' => 'buyer',
            ]
        );

        // Create additional random users
        User::factory(5)->create();
    }
}
