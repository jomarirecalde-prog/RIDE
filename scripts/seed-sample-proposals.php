<?php

/**
 * Seed sample proposals for demo project leader accounts.
 * Run: c:\xampp\php\php.exe scripts\seed-sample-proposals.php
 */

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$stats = \App\Support\SampleProposals::seedMissing();

echo "Sample proposal seeding complete.\n";
echo "Created: {$stats['created']}\n";
echo "Skipped existing: {$stats['skipped']}\n";
echo "Missing users: {$stats['missing_users']}\n";
