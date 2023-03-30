<?php

namespace Lemuel\Notification\Contracts;

use Lemuel\Notification\Events\OrderStatusUpdated;

interface TeamsMessagePayload
{
    public function getMessage(OrderStatusUpdated $event): array;
}
