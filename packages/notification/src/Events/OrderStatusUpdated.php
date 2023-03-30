<?php

namespace Lemuel\Notification\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $uuid;

    public $status;

    public $timestamp;

    public function __construct(string $uuid, string $status, int $timestamp)
    {
        $this->uuid = $uuid;
        $this->status = $status;
        $this->timestamp = $timestamp;
    }
}
