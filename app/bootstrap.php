<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require BASE_PATH . '/app/helpers.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $_POST !== []) {
    normalize_request_post_text_fields($_POST);
}

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$config = require APP_PATH . '/config/config.php';
\App\Core\Database::init($config['database']);
