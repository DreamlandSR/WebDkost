<?php
return [
    'paths' => ['api/*', 'storage/*'],  // ← hanya satu, gabungkan
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
    'Content-Type' => 'image/jpeg',
];