<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'role' => 'buyer'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status','message','data'=>['user','token']]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email'=>$user->email,
            'password'=>'password'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status','message','data'=>['user','token']]);
    }
}
