<?php

namespace Lemuel\Notification\Tests\Stubs;

use Lemuel\Notification\Contracts\TeamsMessagePayload;
use Lemuel\Notification\Events\OrderStatusUpdated;

class FakeTeamMessagePayload implements TeamsMessagePayload
{
    public function getMessage(OrderStatusUpdated $event): array
    {
        return [
            'foo' => 'bar',
        ];
    }
}
