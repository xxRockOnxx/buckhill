<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/user/create', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => $user->address,
            'phone_number' => $user->phone_number,
            'avatar' => $user->avatar,
            'is_marketing' => $user->is_marketing,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function test_cannot_create_user_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/user/create', [
            'email' => 'john.doe',
            'password' => 'password',
            'password_confirmation' => 'password123',
            'avatar' => 'https://example.com/avatar.png',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'password_confirmation',
                'address',
                'phone_number',
                'avatar',
            ]);
    }

    public function test_can_login()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token']);
    }

    public function test_can_get_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/user');

        $response->assertOk();
        $response->assertJson($user->toArray());
    }

    public function test_cannot_get_authenticated_user_without_token()
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(401);
    }
}
