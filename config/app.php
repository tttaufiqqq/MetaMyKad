<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'MetaMyKad',
    'env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOL),
    'base_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Singapore',
];
