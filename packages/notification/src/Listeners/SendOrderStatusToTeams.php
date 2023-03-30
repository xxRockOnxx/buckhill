<?php

namespace Lemuel\Notification\Listeners;

use Illuminate\Http\Client\Factory;
use Lemuel\Notification\Events\OrderStatusUpdated;
use Lemuel\Notification\SimpleTeamsMessagePayload;

class SendOrderStatusToTeams
{
    private Factory $http;

    private string $webhook;

    private string $messageClass;

    public function __construct(Factory $http, string $webhook, ?string $messageClass = null)
    {
        $this->http = $http;
        $this->webhook = $webhook;
        $this->messageClass = $messageClass ?? SimpleTeamsMessagePayload::class;
    }

    public function handle(OrderStatusUpdated $event)
    {
        /** @var TeamsMessagePayload $message */
        $message = new $this->messageClass($event);

        $this->http->post($this->webhook, $message->getMessage($event));
    }
}
