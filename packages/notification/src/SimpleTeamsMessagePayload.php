<?php

namespace Lemuel\Notification;

use Carbon\Carbon;
use Lemuel\Notification\Contracts\TeamsMessagePayload;
use Lemuel\Notification\Events\OrderStatusUpdated;

class SimpleTeamsMessagePayload implements TeamsMessagePayload
{
    public function getMessage(OrderStatusUpdated $event): array
    {
        $carbon = Carbon::createFromTimestamp($event->timestamp);
        $iso8601 = $carbon->toIso8601String();

        return [
            '@type' => 'MessageCard',
            '@context' => 'https://schema.org/extensions',
            'title' => 'Order Status Updated',
            'summary' => "Order {$event->uuid} status update",
            'sections' => [
                [
                    'facts' => [
                        [
                            'name' => 'Order:',
                            'value' => $event->uuid,
                        ],
                        [
                            'name' => 'Status:',
                            'value' => $event->status,
                        ],
                        [
                            'name' => 'Date/Time:',
                            'value' => "{{DATE({$iso8601}, SHORT)}} at {{TIME({$iso8601})}}",
                        ],
                    ],
                ],
            ],
        ];
    }
}
