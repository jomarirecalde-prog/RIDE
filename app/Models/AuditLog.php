<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Auth;
use App\Core\Database;

final class AuditLog
{
    public static function record(string $entityType, ?int $entityId, string $action, array $details = []): void
    {
        $user = Auth::user();
        $stmt = Database::pdo()->prepare(
            'INSERT INTO audit_logs (user_id, entity_type, entity_id, action, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $user['id'] ?? null,
            $entityType,
            $entityId,
            $action,
            json_encode($details, JSON_THROW_ON_ERROR),
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}
