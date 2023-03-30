<?php

use Lemuel\Notification\SimpleTeamsMessagePayload;

return [
    'teams' => [
        // (Required) The webhook URL for the channel
        'webhook' => '',

        'message_class' => SimpleTeamsMessagePayload::class
    ]
];
