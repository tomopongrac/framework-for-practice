<?php

return [
    'mysql' => [
        'driver' => env('DB_DRIVER', 'pdo_mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'dbname' => env('DB_DATABASE', 'database'),
        'user' => env('DB_USER', 'root'),
        'password' => env('DB_PASSWORD', 'root'),
        'port' => env('DB_PORT', 3306),
    ],
    'sqlite' => [
        'driver' => env('DB_DRIVER', 'pdo_sqlite'),
        'user' => env('DB_USER', 'root'),
        'password' => env('DB_PASSWORD', 'root'),
        'path' => base_path('database/'.env('DB_DATABASE')),
    ],
];
