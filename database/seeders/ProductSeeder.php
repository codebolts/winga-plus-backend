<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user as seller (assuming there's at least one user)
        $seller = User::first();
        if (!$seller) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'price' => 999.99,
                'stock' => 50,
                'category_id' => 1, // Electronics
                'subcategory_id' => 1, // Smartphones
                'seller_id' => $seller->id,
                'description' => 'Latest iPhone with advanced features',
                'custom_attributes' => [
                    'color' => 'Space Black',
                    'storage' => '256GB',
                    'warranty' => '1 year'
                ]
            ],
            [
                'name' => 'MacBook Pro 16"',
                'price' => 2499.99,
                'stock' => 20,
                'category_id' => 1, // Electronics
                'subcategory_id' => 2, // Laptops
                'seller_id' => $seller->id,
                'description' => 'Powerful laptop for professionals',
                'custom_attributes' => [
                    'processor' => 'M3 Pro',
                    'ram' => '16GB',
                    'ssd' => '512GB'
                ]
            ],
            [
                'name' => 'Nike Running Shoes',
                'price' => 129.99,
                'stock' => 100,
                'category_id' => 2, // Clothing
                'subcategory_id' => 4, // Men (assuming IDs)
                'seller_id' => $seller->id,
                'description' => 'Comfortable running shoes',
                'custom_attributes' => [
                    'size' => '10',
                    'color' => 'Blue',
                    'material' => 'Mesh'
                ]
            ],
            [
                'name' => 'Coffee Maker',
                'price' => 79.99,
                'stock' => 30,
                'category_id' => 3, // Home & Kitchen
                'subcategory_id' => 8, // Kitchen Appliances
                'seller_id' => $seller->id,
                'description' => 'Automatic coffee maker',
                'custom_attributes' => [
                    'capacity' => '12 cups',
                    'type' => 'Drip',
                    'brand' => 'Generic'
                ]
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
