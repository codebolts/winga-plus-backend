<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class SubcategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_subcategory()
    {
        $seller = User::factory()->create(['role'=>'seller']);
        $token = $seller->createToken('test')->plainTextToken;

        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/seller/subcategories', [
                'category_id'=>$category->id,
                'name'=>'Smartphones'
            ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name','Smartphones');
    }

    public function test_anyone_can_list_subcategories()
    {
        $response = $this->getJson('/api/subcategories');
        $response->assertStatus(200);
    }
}
