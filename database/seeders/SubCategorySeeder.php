<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategory;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            // Electronics (1)
            ['name' => 'Smartphones', 'category_id' => 1],
            ['name' => 'Laptops', 'category_id' => 1],
            ['name' => 'Cameras', 'category_id' => 1],
            ['name' => 'Headphones', 'category_id' => 1],
            ['name' => 'Tablets', 'category_id' => 1],

            // Clothing (2)
            ['name' => 'Men', 'category_id' => 2],
            ['name' => 'Women', 'category_id' => 2],
            ['name' => 'Kids', 'category_id' => 2],
            ['name' => 'Shoes', 'category_id' => 2],
            ['name' => 'Accessories', 'category_id' => 2],

            // Home & Kitchen (3)
            ['name' => 'Furniture', 'category_id' => 3],
            ['name' => 'Kitchen Appliances', 'category_id' => 3],
            ['name' => 'Bedding', 'category_id' => 3],
            ['name' => 'Decor', 'category_id' => 3],

            // Books (4)
            ['name' => 'Fiction', 'category_id' => 4],
            ['name' => 'Non-Fiction', 'category_id' => 4],
            ['name' => 'Textbooks', 'category_id' => 4],
            ['name' => 'Comics', 'category_id' => 4],

            // Sports & Outdoors (5)
            ['name' => 'Fitness', 'category_id' => 5],
            ['name' => 'Outdoor Gear', 'category_id' => 5],
            ['name' => 'Team Sports', 'category_id' => 5],
            ['name' => 'Cycling', 'category_id' => 5],

            // Beauty & Personal Care (6)
            ['name' => 'Skincare', 'category_id' => 6],
            ['name' => 'Hair Care', 'category_id' => 6],
            ['name' => 'Makeup', 'category_id' => 6],
            ['name' => 'Fragrance', 'category_id' => 6],

            // Automotive (7)
            ['name' => 'Car Parts', 'category_id' => 7],
            ['name' => 'Tools', 'category_id' => 7],
            ['name' => 'Electronics', 'category_id' => 7],
            ['name' => 'Tires', 'category_id' => 7],

            // Toys & Games (8)
            ['name' => 'Action Figures', 'category_id' => 8],
            ['name' => 'Board Games', 'category_id' => 8],
            ['name' => 'Puzzles', 'category_id' => 8],
            ['name' => 'Building Sets', 'category_id' => 8],

            // Health & Household (9)
            ['name' => 'Vitamins', 'category_id' => 9],
            ['name' => 'Cleaning Supplies', 'category_id' => 9],
            ['name' => 'First Aid', 'category_id' => 9],
            ['name' => 'Personal Care', 'category_id' => 9],

            // Grocery & Gourmet Food (10)
            ['name' => 'Snacks', 'category_id' => 10],
            ['name' => 'Beverages', 'category_id' => 10],
            ['name' => 'Organic', 'category_id' => 10],
            ['name' => 'Baking', 'category_id' => 10],

            // Pet Supplies (11)
            ['name' => 'Dog Food', 'category_id' => 11],
            ['name' => 'Cat Food', 'category_id' => 11],
            ['name' => 'Pet Toys', 'category_id' => 11],
            ['name' => 'Pet Beds', 'category_id' => 11],

            // Office Products (12)
            ['name' => 'Stationery', 'category_id' => 12],
            ['name' => 'Furniture', 'category_id' => 12],
            ['name' => 'Electronics', 'category_id' => 12],
            ['name' => 'Printers', 'category_id' => 12],

            // Tools & Home Improvement (13)
            ['name' => 'Power Tools', 'category_id' => 13],
            ['name' => 'Hand Tools', 'category_id' => 13],
            ['name' => 'Hardware', 'category_id' => 13],
            ['name' => 'Plumbing', 'category_id' => 13],

            // Baby Products (14)
            ['name' => 'Diapers', 'category_id' => 14],
            ['name' => 'Clothing', 'category_id' => 14],
            ['name' => 'Furniture', 'category_id' => 14],
            ['name' => 'Feeding', 'category_id' => 14],

            // Arts, Crafts & Sewing (15)
            ['name' => 'Painting', 'category_id' => 15],
            ['name' => 'Drawing', 'category_id' => 15],
            ['name' => 'Sewing', 'category_id' => 15],
            ['name' => 'Craft Supplies', 'category_id' => 15],
        ];

        foreach ($subcategories as $sub) {
            Subcategory::updateOrCreate(
                ['name' => $sub['name'], 'category_id' => $sub['category_id']],
                $sub
            );
        }
    }
}
