<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategory;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            // Electronics
            ['name' => 'Smartphones', 'category_id' => 1],
            ['name' => 'Laptops', 'category_id' => 1],
            ['name' => 'Cameras', 'category_id' => 1],

            // Clothing
            ['name' => 'Men', 'category_id' => 2],
            ['name' => 'Women', 'category_id' => 2],
            ['name' => 'Kids', 'category_id' => 2],

            // Home & Kitchen
            ['name' => 'Furniture', 'category_id' => 3],
            ['name' => 'Kitchen Appliances', 'category_id' => 3],

            // Books
            ['name' => 'Fiction', 'category_id' => 4],
            ['name' => 'Non-Fiction', 'category_id' => 4],

            // Sports & Outdoors
            ['name' => 'Fitness', 'category_id' => 5],
            ['name' => 'Outdoor Gear', 'category_id' => 5],
        ];

        foreach ($subcategories as $sub) {
            Subcategory::create($sub);
        }
    }
}
