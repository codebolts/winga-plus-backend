<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_place_order()
    {
        $buyer = User::factory()->create(['role'=>'buyer']);
        $token = $buyer->createToken('test')->plainTextToken;

        $seller = User::factory()->create(['role'=>'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['seller_id'=>$seller->id,'category_id'=>$category->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/buyer/orders', [
                'items'=>[
                    ['product_id'=>$product->id,'quantity'=>2]
                ]
            ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['status','message','data'=>['items']]);
    }
}
