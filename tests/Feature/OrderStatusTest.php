<?php

namespace Tests\Feature;

use App\Models\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_order_statuses()
    {
        // Arrange
        $this->seed();

        // Act
        $response = $this->getJson('/api/v1/order-statuses?page=2&limit=2&sortBy=title');

        // Assert
        $response->assertOk();

        $status = OrderStatus::query()
            ->orderBy('title', 'asc')
            ->paginate(2, ['*'], 'page', 2);

        $response->assertJson(collect($status)->toArray());
    }

    public function test_can_get_order_status()
    {
        // Arrange
        $this->seed();

        $status = OrderStatus::query()->inRandomOrder()->first();

        // Act
        $response = $this->getJson('/api/v1/order-status/' . $status->uuid);

        // Assert
        $this->assertSuccessResponseMacro($response, $status->toArray());
    }

    public function test_can_not_get_invalid_order_status()
    {
        // Arrange
        $this->seed();

        // Act
        $response = $this->getJson('/api/v1/order-status/' . '00000000-0000-0000-0000-000000000000');

        // Assert
        $this->assertErrorResponseMacro($response, 404, 'Order status not found');
    }
}
