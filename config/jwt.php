<?php

return [
    'public_key' => storage_path('app/jwt/jwt.pub'),
    'private_key' => storage_path('app/jwt/jwt.key'),

    // Minutes from now before the token expires
    'ttl' => 60,
];
