<?php

namespace Tests\Feature;

use App\Models\Order;
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

    public function test_can_get_authenticated_user_orders()
    {
        // Arrange
        $this->seed();

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $orders = Order::factory()
            ->count(10)
            ->for($user)
            ->create();

        $orders2 = Order::factory()
            ->count(3)
            ->for($user2)
            ->create();

        // Act
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/user/orders?page=1&limit=5');

        // Assert
        $response->assertOk();
        $this->assertEquals(10, $response->json('total'));
        $this->assertEquals(5, $response->json('per_page'));
        $this->assertEquals(1, $response->json('current_page'));
        $this->assertEquals(2, $response->json('last_page'));
        $this->assertEquals($orders->take(5)->toArray(), $response->json('data'));
        $this->assertNotContains($orders->skip(5)->take(5)->toArray(), $response->json('data'));
        $this->assertNotContains($orders2->toArray(), $response->json('data'));
    }

    public function test_cannot_get_orders_without_token()
    {
        $response = $this->getJson('/api/v1/user/orders');

        $response->assertStatus(401);
    }
}
