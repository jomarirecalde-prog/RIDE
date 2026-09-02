<?php

declare(strict_types=1);

namespace App\Support;

final class SampleProposals
{
    /** @return array<string, array<string, mixed>> */
    public static function all(): array
    {
        return [];
    }

    /** @return array<string, mixed>|null */
    public static function forUser(?array $user): ?array
    {
        return null;
    }

    /** @return array{created: int, skipped: int, missing_users: int} */
    public static function seedMissing(): array
    {
        return ['created' => 0, 'skipped' => 0, 'missing_users' => 0];
    }
}
