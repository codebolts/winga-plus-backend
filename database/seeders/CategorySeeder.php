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
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
