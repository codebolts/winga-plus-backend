<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_add_review()
    {
        $buyer = User::factory()->create(['role'=>'buyer']);
        $token = $buyer->createToken('test')->plainTextToken;

        $seller = User::factory()->create(['role'=>'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['seller_id'=>$seller->id,'category_id'=>$category->id]);

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->postJson("/api/products/{$product->id}/reviews", [
                'rating'=>5,
                'comment'=>'Excellent product!'
            ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.rating',5);
    }

    public function test_anyone_can_list_reviews()
    {
        $product = Product::factory()->create();
        $response = $this->getJson("/api/products/{$product->id}/reviews");
        $response->assertStatus(200);
    }
}
