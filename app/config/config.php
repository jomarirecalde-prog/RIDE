<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Research & Extension Monitoring System',
        'url' => 'http://localhost/RIDE/public',
        'timezone' => 'Asia/Manila',
    ],
    'database' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'ride_ims',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'session' => [
        'lifetime' => 7200,
    ],
];
