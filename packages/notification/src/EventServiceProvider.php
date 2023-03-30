<?php

namespace Lemuel\Notification;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Lemuel\Notification\Events\OrderStatusUpdated;
use Lemuel\Notification\Listeners\SendOrderStatusToTeams;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderStatusUpdated::class => [
            SendOrderStatusToTeams::class,
        ],
    ];
}
