<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$sql = file_get_contents(dirname(__DIR__) . '/database/migrations/phase5.sql');

try {
    \App\Core\Database::pdo()->exec($sql);
    echo "Migration phase5 applied.\n";
} catch (\Throwable $e) {
    echo 'Migration failed: ' . $e->getMessage() . "\n";
    exit(1);
}
