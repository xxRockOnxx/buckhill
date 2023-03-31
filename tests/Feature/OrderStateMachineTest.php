<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderStatus;
use Database\Seeders\OrderStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStateMachineTest extends TestCase
{
    use RefreshDatabase;

    public function test_state_machine_works_for_order_status()
    {
        $this->seed(OrderStatusSeeder::class);

        $order = Order::factory()->create([
            'order_status_id' => OrderStatus::where('title', 'open')->first()->id,
        ]);

        $order->setGraph([
            'open' => [
                'pending payment',
                'cancelled',
            ],

            'pending payment' => [
                'paid',
                'cancelled',
            ],

            'paid' => [
                'shipped',
                'cancelled'
            ],

            'shipped' => [],

            'cancelled' => []
        ]);

        $next = $order->getNextTransitions()[0];

        $order->transition($next);
        $order->save();
        $order->refresh();

        $this->assertEquals($next, $order->orderStatus->title);
        $this->assertEquals(OrderStatus::where('title', $next)->first()->id, $order->order_status_id);
    }
}
