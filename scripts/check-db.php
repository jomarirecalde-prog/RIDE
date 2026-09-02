<?php
require dirname(__DIR__) . '/app/bootstrap.php';
$tables = ['milestones', 'progress_reports', 'documents', 'notifications'];
foreach ($tables as $t) {
    $ok = \App\Core\Database::pdo()->query("SHOW TABLES LIKE '{$t}'")->fetch();
    echo $t . ': ' . ($ok ? 'OK' : 'MISSING') . PHP_EOL;
}
