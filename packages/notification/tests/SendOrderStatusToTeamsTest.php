<?php

namespace Lemuel\Notification\Tests;

use Illuminate\Support\Facades\Http;
use Lemuel\Notification\Events\OrderStatusUpdated;
use Lemuel\Notification\Listeners\SendOrderStatusToTeams;
use Lemuel\Notification\SimpleTeamsMessagePayload;
use Lemuel\Notification\Tests\Stubs\FakeTeamMessagePayload;
use Orchestra\Testbench\TestCase;

class SendOrderStatusToTeamsTest extends TestCase
{
    public function test_it_uses_given_webhook()
    {
        $http = Http::fake([
            'mocked-webhook.com' => Http::response(),
        ]);

        $event = new OrderStatusUpdated('123', 'shipped', 1680163966);
        $listener = new SendOrderStatusToTeams($http, 'mocked-webhook.com');

        $listener->handle($event);

        $http->assertSent(function ($request) {
            return $request->url() === 'mocked-webhook.com';
        });
    }

    public function test_it_uses_given_message_class()
    {
        $http = Http::fake([
            'mocked-webhook.com' => Http::response(),
        ]);

        $event = new OrderStatusUpdated('123', 'shipped', 1680163966);
        $listener = new SendOrderStatusToTeams($http, 'mocked-webhook.com', SimpleTeamsMessagePayload::class);
        $listener2 = new SendOrderStatusToTeams($http, 'mocked-webhook.com', FakeTeamMessagePayload::class);

        $listener->handle($event);
        $listener2->handle($event);

        /** @var Request $request1 */
        [$request1] = $http->recorded()->get(0);

        /** @var Request $request2 */
        [$request2] = $http->recorded()->get(1);

        $this->assertEquals((new SimpleTeamsMessagePayload())->getMessage($event), $request1->data());
        $this->assertEquals((new FakeTeamMessagePayload())->getMessage($event), $request2->data());
    }
}
