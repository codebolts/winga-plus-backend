<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_category()
    {
        $seller = User::factory()->create(['role'=>'seller']);
        $token = $seller->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/seller/categories', [
                'name' => 'Electronics',
                'description' => 'Devices and gadgets'
            ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name','Electronics');
    }

    public function test_anyone_can_list_categories()
    {
        Category::factory()->create(['name'=>'Fashion']);

        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
                 ->assertJsonStructure(['status','message','data']);
    }
}
