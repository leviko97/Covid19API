<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected string $password = 'P@ssword12';

    public function test_create_user(){
        $response = $this->post('/api/auth/register', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'Abcd12#$',
            'password_confirmation' => 'Abcd12#$',
        ]);

        $response->assertStatus(201);
    }

    public function test_create_existing_user(){
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ]);

        $response->assertStatus(422);
    }

    public function test_login_user(){
        $user = User::factory()->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function test_login_with_wrong_credentials(){
        $this->post('/api/auth/login', [
            'email' => 'something@does.not.exists',
            'password' => 'pss'
        ])->assertStatus(422);
    }

    public function test_logout_user(){
        Sanctum::actingAs(
            User::factory()->create()
        );

        $this->post('/api/auth/logout')->assertOk();
    }

    public function test_logout_as_guest(){
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/auth/logout');

        $response->assertStatus(401);
    }
}
