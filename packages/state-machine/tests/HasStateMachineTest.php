<?php

namespace Lemuel\StateMachine\Tests;

use Lemuel\StateMachine\Tests\Stubs\Order;
use Orchestra\Testbench\TestCase;

class HasStateMachineTest extends TestCase
{
    public function test_can_get_next_transitions()
    {
        $order = new Order();

        $order->setGraph([
            'pending' => [
                'paid',
                'cancelled',
            ],
            'paid' => [
                'shipped',
                'cancelled',
            ],
            'shipped' => [
                'delivered',
                'cancelled',
            ],
        ]);

        $order->setCurrentState('pending');

        $this->assertEquals(['paid', 'cancelled'], $order->getNextTransitions());
    }

    public function test_can_transition()
    {
        $order = new Order();

        $order->setGraph([
            'pending' => [
                'paid',
                'cancelled',
            ],
            'paid' => [
                'shipped',
                'cancelled',
            ],
            'shipped' => [
                'delivered',
                'cancelled',
            ],
        ]);

        $order->setCurrentState('pending');

        $this->assertTrue($order->canTransition('paid'));
        $this->assertFalse($order->canTransition('shipped'));

        $order->transition('paid', function () {
            $this->assertTrue(true, 'Callback was called');
        });

        $this->assertEquals('paid', $order->getCurrentState());
    }
}
