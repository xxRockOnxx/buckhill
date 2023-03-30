<?php

namespace Lemuel\Notification\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated
{
    use Dispatchable, SerializesModels;

    public string $uuid;

    public string $status;

    public int $timestamp;

    public function __construct(string $uuid, string $status, int $timestamp)
    {
        $this->uuid = $uuid;
        $this->status = $status;
        $this->timestamp = $timestamp;
    }
}
