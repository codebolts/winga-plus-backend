<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Clothing'],
            ['name' => 'Home & Kitchen'],
            ['name' => 'Books'],
            ['name' => 'Sports & Outdoors'],
            ['name' => 'Beauty & Personal Care'],
            ['name' => 'Automotive'],
            ['name' => 'Toys & Games'],
            ['name' => 'Health & Household'],
            ['name' => 'Grocery & Gourmet Food'],
            ['name' => 'Pet Supplies'],
            ['name' => 'Office Products'],
            ['name' => 'Tools & Home Improvement'],
            ['name' => 'Baby Products'],
            ['name' => 'Arts, Crafts & Sewing'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
