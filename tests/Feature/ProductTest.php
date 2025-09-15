<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_product()
    {
        $seller = User::factory()->create(['role'=>'seller']);
        $token = $seller->createToken('test')->plainTextToken;

        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id'=>$category->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/seller/products', [
                'name'=>'iPhone',
                'price'=>1200,
                'stock'=>10,
                'category_id'=>$category->id,
                'subcategory_id'=>$subcategory->id
            ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name','iPhone');
    }

    public function test_buyer_can_list_products()
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    }
}
