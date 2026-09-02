<?php

/**
 * Run: c:\xampp\php\php.exe scripts/install.php
 */

declare(strict_types=1);

$base = dirname(__DIR__);
require $base . '/app/bootstrap.php';

echo "RIDE IMS database setup\n";
echo "Database: " . config('database.name') . "\n";
echo "Done. Open " . config('app.url') . "/login\n";
