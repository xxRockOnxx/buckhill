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
        // Arrange
        $user = User::factory()->make();

        // Act
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

        // Assert
        $data = $user->toArray();
        $data['email_verified_at'] = null;
        $data['uuid'] = true;

        $this->assertSuccessResponseMacro($response, $data);

        // Make sure the user is actually created in the database
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

        $this->assertErrorResponseMacro($response, 422, 'Failed Validation', [
            'first_name' => true,
            'last_name' => true,
            'email' => true,
            'password_confirmation' => true,
            'address' => true,
            'phone_number' => true,
            'avatar' => true,
        ]);
    }

    public function test_can_login()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->postJson('/api/v1/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Assert
        $this->assertSuccessResponseMacro($response, [
            'token' => true,
        ]);
    }

    public function test_can_get_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/user');

        $response->assertOk();
        $response->assertJsonMissing([
            'id',
            'password',
            'remember_token',
            'is_admin',
        ]);
        $response->assertJson($user->toArray());
    }

    public function test_cannot_get_authenticated_user_without_token()
    {
        $response = $this->getJson('/api/v1/user');

        $this->assertErrorResponseMacro($response, 401, 'Unauthorized');
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

        $paginator = $user->orders()
            ->orderBy('created_at')
            ->paginate(5, ['*'], 'page', 1);

        $response->assertJson(collect($paginator)->toArray());
    }

    public function test_cannot_get_orders_without_token()
    {
        $response = $this->getJson('/api/v1/user/orders');

        $this->assertErrorResponseMacro($response, 401, 'Unauthorized');
    }
}
